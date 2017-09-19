<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Dtos;

use AppBundle\Entity\Contacto;
use AppBundle\Entity\ContactoRespuesta;
use AppBundle\Entity\Encuesta;
use AppBundle\Entity\Pregunta;
use AppBundle\Entity\Respuesta;

class DtoEncuestaTxt
{
    protected $mensaje;

    public function __construct($array)
    {
        $i= 0;
        /**@var \AppBundle\Entity\ContactoRespuesta $cr*/
        foreach($array as $cr){
            $i++;
            $this->mensaje .=
                '<p>Contacto: ' . $cr->getContacto()->getName() . ' | ' .
                'Encuesta: ' . $cr->getRespuesta()->getPregunta()->getEncuesta()->getTitulo() . ' | ' .
                'Pregunta ' . $i . ' : ' . $cr->getRespuesta()->getPregunta()->getTitulo() . ' | ' .
                'Respuesta: ' . $cr->getRespuesta()->getTitulo() . '</p>';
        }

    }


    public function __toString()
    {
        return $this->mensaje;
    }

}