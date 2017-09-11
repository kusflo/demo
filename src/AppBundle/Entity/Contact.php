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
class Contact
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
     * @var string The actual name of the contact
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=false)
     */
    private $name;



    /**
     * @var string The actual mail of the contact
     *
     * @Assert\Type(type="string")
     * @ORM\Column(nullable=false)
     */
    private $mail;

    /**
     * @var string of the contact
     *
     * @ORM\Column(nullable=false, type="text")
     */
    private $description;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Contact
     */
    public function setName(string $name): Contact
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     * @return Contact
     */
    public function setMail(string $mail): Contact
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Contact
     */
    public function setDescription(string $description): Contact
    {
        $this->description = $description;

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
     * @return Contact
     */
    public function setPublicationDate(\DateTime $publicationDate)
    {
        $this->publicationDate = $publicationDate;
        return $this;
    }
}
