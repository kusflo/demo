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

        $repoContactoRespuestas = $this->getDoctrine()->getRepository(ContactoRespuesta::class);
        $contactoRespuestas = $repoContactoRespuestas->findAll();
        $objPHPExcel = new PHPExcel();
        $obj = new GenerateExcelOfContactoRespuestas($objPHPExcel, $contactoRespuestas);
        $objPHPExcel = $obj->getObjPHPExcel();

        return $this->generateOutputExcel($objPHPExcel);

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


}