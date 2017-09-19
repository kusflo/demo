<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Pregunta
 * @ORM\Entity
 * @ORM\Table(name="preguntas")
 */
class Pregunta
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $titulo;


    /**
     * @ORM\OneToMany(targetEntity="Respuesta", mappedBy="pregunta")
     */
    protected $respuestas;


    /**
     * @ORM\ManyToOne(targetEntity="Encuesta", inversedBy="preguntas")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")
     */
    protected $encuesta;


    public function __construct()
    {
        $this->respuestas = new ArrayCollection();
    }


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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Pregunta
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set encuesta
     *
     * @param \AppBundle\Entity\Encuesta $encuesta
     *
     * @return Pregunta
     */
    public function setEncuesta(\AppBundle\Entity\Encuesta $encuesta = null)
    {
        $this->encuesta = $encuesta;

        return $this;
    }

    /**
     * Get encuesta
     *
     * @return \AppBundle\Entity\Encuesta
     */
    public function getEncuesta()
    {
        return $this->encuesta;
    }

    /**
     * Add respuesta
     *
     * @param \AppBundle\Entity\Respuesta $respuesta
     *
     * @return Pregunta
     */
    public function addRespuesta(\AppBundle\Entity\Respuesta $respuesta)
    {
        $this->respuestas[] = $respuesta;

        return $this;
    }

    /**
     * Remove respuesta
     *
     * @param \AppBundle\Entity\Respuesta $respuesta
     */
    public function removeRespuesta(\AppBundle\Entity\Respuesta $respuesta)
    {
        $this->respuestas->removeElement($respuesta);
    }

    /**
     * Get respuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRespuestas()
    {
        return $this->respuestas;
    }
}
