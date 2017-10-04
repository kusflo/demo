<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{

    public function getReportAction(){

        $html = file_get_contents(__DIR__ . '/../Resources/views/preguntasRespuestas.html');
        return new Response($html);
    }

    public function getReportGraficoAction(){

        $html = file_get_contents(__DIR__ . '/../Resources/views/graficoRespuestas.html');
        return new Response($html);
    }


}