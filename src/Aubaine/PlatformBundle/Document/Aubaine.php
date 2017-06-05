<?php

namespace Aubaine\PlatformBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(repositoryClass="Aubaine\PlatformBundle\Repository\AubaineRepository")
 */
class Aubaine
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $idAuthor;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(min=10)
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $date;

    /** 
    * @MongoDB\ReferenceOne(targetDocument="Aubaine\UserBundle\Document\User")
    * @Assert\Valid()
    */
    protected $author;


    public function __construct()
	  {
        $this->date         = new \Datetime();
	  }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string $message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set author
     *
     * @param Aubaine\UserBundle\Document\User $author
     * @return $this
     */
    public function setAuthor(\Aubaine\UserBundle\Document\User $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return Aubaine\UserBundle\Document\User $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set idAuthor
     *
     * @param string $idAuthor
     * @return $this
     */
    public function setIdAuthor($idAuthor)
    {
        $this->idAuthor = $idAuthor;
        return $this;
    }

    /**
     * Get idAuthor
     *
     * @return string $idAuthor
     */
    public function getIdAuthor()
    {
        return $this->idAuthor;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }
}
