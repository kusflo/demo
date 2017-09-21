<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Services;


use PHPExcel_IOFactory;

class ReaderExcelOfFile
{
    private $objPHPExcel;

    /**
     * ReaderExcelOfFile constructor.
     * @param $path String
     */
    public function __construct($path)
    {

        try {
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $this->objPHPExcel = $objReader->load($path);
        } catch(\Exception $e) {
            die('Error loading file "'.pathinfo($path,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

    }


    public function getArrayData(){
        $result = array();
        $sheet = $this->objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                NULL,
                TRUE,
                FALSE);
            $result [] = $rowData[0];
        }

        return $result;

    }
}