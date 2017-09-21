<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Services;


use AppBundle\Entity\ContactoRespuesta;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

class GenerateExcelOfContactoRespuestas
{
    private $objPHPExcel;
    private $contactoRespuestas;
    private $file;


    /**
     * GenerateExcelOfContactoRespuestas constructor.
     * @param PHPExcel $objPHPExcel
     * @param ContactoRespuesta [] $contactoRespuestas
     */
    public function __construct($objPHPExcel, $contactoRespuestas)
    {
        $this->file = 1;
        $this->objPHPExcel = $objPHPExcel;
        $this->contactoRespuestas = $contactoRespuestas;
        $this->infoMetaDataExcel();
        $this->infoHeader();
        $this->sizeColumns();
        $this->infoDataExcel();
        $this->applyFormat();
    }


    public function getObjPHPExcel()
    {
        return $this->objPHPExcel;
    }

    private function infoMetaDataExcel()
    {
        $this->objPHPExcel->getProperties()->setCreator("Marcos Redondo")
            ->setLastModifiedBy("Marcos Redondo")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $this->objPHPExcel->getActiveSheet()->setTitle('Encuesta');

    }

    private function infoHeader()
    {
        $this->objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Contacto')
            ->setCellValue('B1', 'Encuesta')
            ->setCellValue('C1', 'Pregunta')
            ->setCellValue('D1', 'Respuesta');
    }


    private function sizeColumns()
    {
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    }

    private function infoDataExcel()
    {

        foreach($this->contactoRespuestas as $res){
            ++$this->file;
            $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$this->file, $res->getContacto()->getName())
                ->setCellValue('B'.$this->file, $res->getRespuesta()->getPregunta()->getEncuesta()->getTitulo())
                ->setCellValue('C'.$this->file, $res->getRespuesta()->getPregunta()->getTitulo())
                ->setCellValue('D'.$this->file, $res->getRespuesta()->getTitulo());
        }

    }

    private function applyFormat()
    {
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle('A1:D' . $this->file)->applyFromArray(
            array(
                'fill' 	=> array(
                            'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
                            'color'	=> array('argb' => 'FFFFFF00')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    ),
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );
    }


}