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
    protected $placeId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @MongoDB\Field(type="bool")
     */
    protected $permanent = False;


    /**
     * @MongoDB\Field(type="date")
     */
    protected $start;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $end;

    /** 
    * @MongoDB\ReferenceOne(targetDocument="Aubaine\PlaceBundle\Document\Place")
    * @Assert\Valid()
    */
    protected $place;


    public function __construct()
	  {
        $this->start         = new \Datetime();
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
     * Set place
     *
     * @param string $place
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * Get place
     *
     * @return string $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set placeid
     *
     * @param string $placeId
     * @return $this
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;
        return $this;
    }

    /**
     * Get placeid
     *
     * @return string $placeId
     */
    public function getPlaceId()
    {
        return $this->placeId;
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
     * @param string $City
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get City
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
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

    /**
     * Set end
     *
     * @param end $end
     * @return $this
     */
    public function setPermanent($permanent)
    {
        $this->permanent = $permanent;
        return $this;
    }

    /**
     * Get permanent
     *
     * @return permanent $permanent
     */
    public function getPermanent()
    {
        return $this->permanent;
    }

    /**
     * Set end
     *
     * @param end $end
     * @return $this
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * Get end
     *
     * @return end $end
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set start
     *
     * @param start $start
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * Get start
     *
     * @return start $start
     */
    public function getStart()
    {
        return $this->start;
    }
}
