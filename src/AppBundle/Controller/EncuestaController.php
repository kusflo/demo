<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Controller;



use AppBundle\Dtos\DtoEncuestaTxt;
use AppBundle\Entity\Contacto;
use AppBundle\Entity\ContactoRespuesta;
use AppBundle\Entity\Encuesta;
use AppBundle\Entity\Pregunta;
use AppBundle\Entity\Respuesta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EncuestaController extends Controller
{
    public function getByIdAction(){
        $response = new Response(json_encode(array('name' => 'Marcos')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function addAction(){

        /*Creamos una encuesta */
        $em = $this->getDoctrine()->getManager();
        $titulo = 'Encuesta 1';
        $descripcion = 'Prueba Encuesta 1';
        $preguntas = array (
                        'preguntas' => array(
                            '¿Cual de estas comidas te gusta más?',
                            '¿Cual de estos colores te gusta mas?',
                            '¿Cual de estos transportes te gusta más?'
                        ),
                        'opciones' => array(
                            array('Pescado', 'Carne', 'Pasta'),
                            array('Rojo', 'Verde', 'Azul'),
                            array('Coche', 'Barco', 'Avion')
                        ));


        $encuesta = new Encuesta();
        $encuesta->setTitulo($titulo)->setDescripcion($descripcion);
        $em->persist($encuesta);
        for($i = 0; $i < count($preguntas['preguntas']); $i++){
            $pregunta = new Pregunta();
            $pregunta->setTitulo($preguntas['preguntas'][$i]);
            $pregunta->setEncuesta($encuesta);
            $em->persist($pregunta);
            for($j = 0; $j < count($preguntas['opciones'][$i]); $j++){
               $respuesta = new Respuesta();
               $respuesta->setTitulo($preguntas['opciones'][$i][$j]);
               $respuesta->setPregunta($pregunta);
               $em->persist($respuesta);
               unset($respuesta);
            }
            unset($pregunta);
        }

        $em->flush();
        $repoEncuesta = $this->getDoctrine()->getRepository(Encuesta::class);
        $encuesta = $repoEncuesta->find($encuesta->getId());
        dump($encuesta);
        return new Response ('OK');
    }


    public function getAction($id){
        $repoEncuesta = $this->getDoctrine()->getRepository(Encuesta::class);
        /**@var \AppBundle\Entity\Encuesta $encuesta*/
//        Objeto encuesta sin hidratación de preguntas y respuestas
        $encuesta = $repoEncuesta->find($id);
//        Objeto encuesta con hidratación de preguntas
//        $preguntas = $encuesta->getPreguntas()->getValues();
//        Objeto encuesta con hidratación de respuestas
        $preguntas = $encuesta->getPreguntas();
        $respuestas = $preguntas[0]->getRespuestas()->getValues();
        dump($encuesta);
        return new Response('<html><body><h1>Ver objeto encuesta 1 en la diana de la barra inferior de depuración</h1></body></html>');
    }


    public function getEncuestasByContactoAction($id){

        $repoContacto = $this->getDoctrine()->getRepository(Contacto::class);
        $repoContactoRespuestas = $this->getDoctrine()->getRepository(ContactoRespuesta::class);
        $contacto = $repoContacto->find($id);
        $contactoRespuestas = $repoContactoRespuestas->findByContacto($contacto);
        $dtoForView = new DtoEncuestaTxt($contactoRespuestas);
//        $dtoForView = '';

        return new Response('<html><body><h1>Encuestas por contacto:</h1><p>' . $dtoForView . '</p></body></html>');

    }

}