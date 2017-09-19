<?php
/**
 * @author Marcos Redondo <kusflo@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Pregunta
 * @ORM\Entity
 * @ORM\Table(name="respuestas")
 */
class Respuesta
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
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="respuestas")
     * @ORM\JoinColumn(name="pregunta_id", referencedColumnName="id")
     */
    protected $pregunta;





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
     * @return Respuesta
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
     * Set pregunta
     *
     * @param \AppBundle\Entity\Pregunta $pregunta
     *
     * @return Respuesta
     */
    public function setPregunta(\AppBundle\Entity\Pregunta $pregunta = null)
    {
        $this->pregunta = $pregunta;

        return $this;
    }

    /**
     * Get pregunta
     *
     * @return \AppBundle\Entity\Pregunta
     */
    public function getPregunta()
    {
        return $this->pregunta;
    }

    public function __toString()
    {
        return $this->getTitulo();
    }
}
