<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity
 * @ApiResource
 */
class EncuestaUno
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string Respuesta a la pregunta uno
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=true)
     */
    private $answerOne;



    /**
     * @var string Respuesta a la pregunta dos
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=true)
     */
    private $answerTwo;


    /**
     * @var string Respuesta a la pregunta tres
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=true)
     */
    private $answerThree;


    /**
     * @var string Respuesta a la pregunta cuatro
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=true)
     */
    private $answerFour;

    /**
     * @var string Respuesta a la pregunta cinco
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=true)
     */
    private $answerFive;


    /**
     * @var \DateTime of the form
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $publicationDate;

    
    /**
     * Sets id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAnswerOne(): string
    {
        return $this->answerOne;
    }

    /**
     * @param string $answerOne
     * @return EncuestaUno
     */
    public function setAnswerOne(string $answerOne): EncuestaUno
    {
        $this->answerOne = $answerOne;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnswerTwo(): string
    {
        return $this->answerTwo;
    }

    /**
     * @param string $answerTwo
     * @return EncuestaUno
     */
    public function setAnswerTwo(string $answerTwo): EncuestaUno
    {
        $this->answerTwo = $answerTwo;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnswerThree(): string
    {
        return $this->answerThree;
    }

    /**
     * @param string $answerThree
     * @return EncuestaUno
     */
    public function setAnswerThree(string $answerThree): EncuestaUno
    {
        $this->answerThree = $answerThree;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnswerFour(): string
    {
        return $this->answerFour;
    }

    /**
     * @param string $answerFour
     * @return EncuestaUno
     */
    public function setAnswerFour(string $answerFour): EncuestaUno
    {
        $this->answerFour = $answerFour;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnswerFive(): string
    {
        return $this->answerFive;
    }

    /**
     * @param string $answerFive
     * @return EncuestaUno
     */
    public function setAnswerFive(string $answerFive): EncuestaUno
    {
        $this->answerFive = $answerFive;

        return $this;
    }



    /**
     * Get publicationDate.
     *
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * Set publicationDate.
     *
     * @param \DateTime $publicationDate the value to set
     * @return EncuestaUno
     */
    public function setPublicationDate(\DateTime $publicationDate)
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }
}
