<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Controller;



use AppBundle\Entity\Contacto;
use AppBundle\Entity\ContactoRespuesta;
use AppBundle\Entity\Pregunta;
use AppBundle\Entity\Respuesta;
use AppBundle\Services\GenerateExcelOfContactoRespuestas;
use AppBundle\Services\ReaderExcelOfFile;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPExcel;
use PHPExcel_IOFactory;
use Proxies\__CG__\AppBundle\Entity\Encuesta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExcelController extends Controller
{
    /**@var EntityManager $em **/
    private $em;
    private $repoContacto;
    private $repoEncuesta;
    private $repoPregunta;
    private $repoRespuesta;

    public function getEncuestaAction(){

        ini_set('memory_limit','512M');
        $repoContactoRespuestas = $this->getDoctrine()->getRepository(ContactoRespuesta::class);
        $contactoRespuestas = $repoContactoRespuestas->findAll();
        $objPHPExcel = new PHPExcel();
        $obj = new GenerateExcelOfContactoRespuestas($objPHPExcel, $contactoRespuestas);

        return $this->generateOutputExcel($obj->getObjPHPExcel());

    }

    public function setEncuestaAction(){

        $path = __DIR__ . '/../Resources/excel/encuesta.xls';
        $obj = new ReaderExcelOfFile($path);
        dump($obj->getArrayData());
        return new Response('<html><body>
                <h1>Datos leidos de excel</h1>
                <p>Excel situado en api/src/Resources/excel/encuesta.xls</p>
                <p>Ver los datos en la diana situada en la barra inferior de depuración</p>
                </body></html>');
    }


    public function setEncuestaMasivaFileLoadAction(){
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_2.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_3.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_4.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_5.xls';

//        $tableName = $this->getTableName("temp_encuesta_masiva");

        $this->createTable();
        foreach($paths as $file){
//            $this->transformXlsToCsv($file);
            $path = substr($file, 0, -3) . 'csv';
            $this->updateCsvInTemporalTable($path);
        }
        $this->processDataAndDeleteTable();

        return new Response('<html><body>
                <h1>Datos transformados de excel a csv, importados a una tabla temporal y tratados mediante procedimientos almacenados.</h1>
                <p>Transformamos datos de excel a csv.</p>
                <p>Aplicamos Load data infile para importar los datos a una tabla temporal.</p>
                <p>Ejecutamos procedimientos almacenados para procesar los datos.</p>
                <p>Limpiamos la tabla temporal.</p>
                </body></html>');

    }

    public function setEncuestaMasivaAction(){
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_2.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_3.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_4.xls';
        $paths [] = __DIR__ . '/../Resources/excel/encuesta_masiva_5.xls';

        foreach($paths as $p){
            $obj = new ReaderExcelOfFile($p);
            $array = $obj->getArrayData();
            array_shift($array);
            $this->persistEncuestas($array);
        }

        return new Response('<html><body>
                <h1>Datos importados de un excel a la bd </h1>
                <p>Excel situado en api/src/Resources/excel/encuesta_masiva.xls</p>
                <p>Excel situado en api/src/Resources/excel/encuesta_masiva_2.xls</p>
                <p>Excel situado en api/src/Resources/excel/encuesta_masiva_3.xls</p>
                <p>Excel situado en api/src/Resources/excel/encuesta_masiva_4.xls</p>
                <p>Excel situado en api/src/Resources/excel/encuesta_masiva_5.xls</p>
                </body></html>');

    }


    private function persistEncuestas($array)
    {
        $i = 1;
        $batchSize = 20;
        $this->em = $this->getDoctrine()->getManager();
        $this->initReposDoctrine();
        foreach($array as $row) {
            $contacto = $this->getOrGenerateContact($row);
            /***************************/
            //Optimizable si pasamos estas consultas a arrays en memoria.
            $encuesta = $this->repoEncuesta->findOneByTitulo($row[2]);
            $pregunta = $this->repoPregunta->findOneBy( array('encuesta' => $encuesta, 'titulo' => $row[3] ) );
            $respuesta = $this->repoRespuesta->findOneBy( array('pregunta' => $pregunta, 'titulo' => $row[4] ) );
            /**************************/
            $contactoRespuesta = new ContactoRespuesta();
            $contactoRespuesta->setContacto($contacto)->setRespuesta($respuesta);
            $this->em->persist($contactoRespuesta);
            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
            $i++;
        }
        $this->em->flush();
        $this->em->clear();
    }


    private function generateOutputExcel($objPHPExcel)
    {
        try {
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_start();
            $objWriter->save('php://output');

            return new Response(
                ob_get_clean(),  // read from output buffer
                200,
                array(
                    'Content-Type' => 'application/vnd.ms-excel',
                    'Content-Disposition' => 'attachment; filename="encuesta.xls"',
                )
            );

        } catch (\Exception $e){
            echo $e->getMessage();
            die();
        }
    }

    /**
     * @param $row array
     * @return Contacto
     */
    private function getOrGenerateContact($row): Contacto
    {
        try {
            $repoContacto = $this->getDoctrine()->getRepository(Contacto::class);
            $contacto = $repoContacto->findOneBy(array('name' => $row[0], 'mail' => $row[1]));
            if (!$contacto instanceof Contacto) {
                $this->em->flush();
                $contacto = new Contacto();
                $contacto->setName($row[0])->setMail($row[1])->setDescription('Descripcion por defecto');
                $this->em->persist($contacto);
                $this->em->flush();
            }
        } catch( \Exception $e) {
            $this->em = $this->getDoctrine()->resetManager();
            $this->initReposDoctrine();
            $contacto = $this->repoContacto->findOneBy(array('name' => $row[0], 'mail' => $row[1]));
//            var_dump($contacto);
//            die();
        } finally {
            return $contacto;
        }

    }

    private function initReposDoctrine()
    {
        $this->repoContacto = $this->getDoctrine()->getRepository(Contacto::class);
        $this->repoEncuesta = $this->getDoctrine()->getRepository(Encuesta::class);
        $this->repoPregunta = $this->getDoctrine()->getRepository(Pregunta::class);
        $this->repoRespuesta = $this->getDoctrine()->getRepository(Respuesta::class);
    }

    /**
     * @param $file
     */
    private function transformXlsToCsv($file)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($file);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save(str_replace('.xls', '.csv', $file));
    }

    /**
     * @param $path
     */
    private function updateCsvInTemporalTable($path)
    {
        try {
            /**@var Connection $conn */
            $conn = $this->get('database_connection');
            /*Load data no se puede incluir en un procedimiento almacenado por eso ejecutamos desde php*/
            $sql = "LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE temp_encuesta_masiva "
                . "FIELDS TERMINATED BY ',' "
                . "ENCLOSED BY '\"' "
                . "LINES TERMINATED BY '\n' IGNORE 1 LINES";
            $conn->executeQuery($sql);
        } catch(\Exception $e){
            echo $e->getMessage();
        }

    }

    private function getTableName($string)
    {
        return $string . '_' . hash('md5', random_int(1, 1000));
    }

    private function createTable()
    {
        try {
            /**@var Connection $conn */
            $conn = $this->get('database_connection');
            $sql = "CREATE TABLE temp_encuesta_masiva ( "
            . "Contacto VARCHAR(255) NULL DEFAULT NULL, "
            . "Mail VARCHAR(255) NULL DEFAULT NULL, "
            . "Encuesta VARCHAR(255) NULL DEFAULT NULL, "
            . "Pregunta VARCHAR(255) NULL DEFAULT NULL, "
            . "Respuesta VARCHAR(255) NULL DEFAULT NULL)";


            $conn->executeQuery($sql);
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    private function processDataAndDeleteTable()
    {
        try {
            /**@var Connection $conn */
            $conn = $this->get('database_connection');
            $sql = "CALL AdminImportEncuestas;";
            $conn->executeQuery($sql);
        } catch(\Exception $e){
            echo $e->getMessage();
        }
    }


}