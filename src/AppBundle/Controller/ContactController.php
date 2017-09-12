<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    public function getByIdAction(Request $data){
        $response = new Response(json_encode(array('name' => 'Marcos')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}