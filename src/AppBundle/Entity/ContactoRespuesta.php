<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Clase ContactoRespuesta
 * @ORM\Entity
 */
class ContactoRespuesta
{
    /**
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Contacto")
     * @ORM\JoinColumn(name="contacto_id", referencedColumnName="id")
     */
    protected $contacto;


    /**
     * @ORM\ManyToOne(targetEntity="Respuesta")
     * @ORM\JoinColumn(name="respuesta_id", referencedColumnName="id")
     */
    protected $respuesta;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $texto;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return ContactoRespuesta
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set contacto
     *
     * @param \AppBundle\Entity\Contacto $contacto
     *
     * @return ContactoRespuesta
     */
    public function setContacto(\AppBundle\Entity\Contacto $contacto = null)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return \AppBundle\Entity\Contacto
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set respuesta
     *
     * @param \AppBundle\Entity\Respuesta $respuesta
     *
     * @return ContactoRespuesta
     */
    public function setRespuesta(\AppBundle\Entity\Respuesta $respuesta = null)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return \AppBundle\Entity\Respuesta
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }
}
