<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Encuesta
 * @ORM\Entity
 * @ORM\Table(name="encuestas")
 */
class Encuesta
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $titulo;

    /**
     * @ORM\Column(type="text")
     */
    protected $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta", mappedBy="encuesta")
     */
    protected $preguntas;


    public function __construct()
    {
        $this->preguntas = new ArrayCollection();
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
     * @return Encuesta
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Encuesta
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add pregunta
     *
     * @param \AppBundle\Entity\Pregunta $pregunta
     *
     * @return Encuesta
     */
    public function addPregunta(\AppBundle\Entity\Pregunta $pregunta)
    {
        $this->preguntas[] = $pregunta;

        return $this;
    }

    /**
     * @param \AppBundle\Entity\Pregunta []
     */
    public function addPreguntas($preguntas){
        foreach($preguntas as $p)
            $this->addPregunta($p);
    }

    /**
     * Remove pregunta
     *
     * @param \AppBundle\Entity\Pregunta $pregunta
     */
    public function removePregunta(\AppBundle\Entity\Pregunta $pregunta)
    {
        $this->preguntas->removeElement($pregunta);
    }

    /**
     * Get preguntas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }
}
