<?php

namespace Aubaine\EventBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @MongoDB\Document(repositoryClass="Aubaine\EventBundle\Repository\EventRepository")
 * @Vich\Uploadable
 */
class Event
{
    /**
      * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
      * @MongoDB\Field(type="bool")
     */
    private $published;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $panel;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(min=10)
     */
    protected $title;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(min=10)
     */
    protected $introduction;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\Length(min=10)
     */
    protected $conclusion;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $information;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $date;    

    /**
     * 
     * @Vich\UploadableField(mapping="event_imageHeader", fileNameProperty="imageHeader")
     */
    private $imageFileHeader;

    /**
     * @MongoDB\Field(type="string")
     */
    private $imageHeader;

    /**
     * 
     * @Vich\UploadableField(mapping="event_image1", fileNameProperty="image1")
     */
    private $imageFile1;

    /**
     * @MongoDB\Field(type="string")
     */
    private $image1;

    /**
     * 
     * @Vich\UploadableField(mapping="event_image2", fileNameProperty="image2")
     */
    private $imageFile2;

    /**
     * @MongoDB\Field(type="string")
     */
    private $image2;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $address;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $zipcode;

    /**
     * 
     * @Vich\UploadableField(mapping="event_thumbnail", fileNameProperty="thumbnail")
     */
    private $imageFileThumbnail;

    /**
     * @MongoDB\Field(type="string")
     */
    private $thumbnail;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $lati;

    /**
     * @MongoDB\Field(type="float")
     */
    protected $longi;

    /**
      * @MongoDB\Field(type="bool")
     */
    protected $localized;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $phone;


    public function __construct()
      {
        $this->date         = new \Datetime();
        $this->published    = false;
        $this->localized    = false;
      }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFileThumbnail(File $image = null)
    {
        $this->imageFileThumbnail = $image;
        
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFileThumbnail()
    {
        return $this->imageFileThumbnail;
    }

    /**
     * @param string $thumbNail
     *
     * @return Product
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $publiShed
     *
     * @return Product
     */
    public function setPublished($published)
    {
        $this->published = $published;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param string $panel
     *
     * @return Product
     */
    public function setPanel($panel)
    {
        $this->panel = $panel;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPanel()
    {
        return $this->panel;
    }

    /**
     * Set lati
     *
     * @param float $lati
     * @return $this
     */
    public function setLati($lati)
    {
        $this->lati = $lati;
        return $this;
    }

    /**
     * Get lati
     *
     * @return float $lati
     */
    public function getLati()
    {
        return $this->lati;
    }

    /**
     * Set longi
     *
     * @param float $longi
     * @return $this
     */
    public function setLongi($longi)
    {
        $this->longi = $longi;
        return $this;
    }

    /**
     * Get longi
     *
     * @return float $longi
     */
    public function getLongi()
    {
        return $this->longi;
    }

    /**
     * Set phone
     *
     * @param int $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return int $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set category
     *
     * @param $string $category
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
     * @return $string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return $this
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string $zipcode
     */
    public function getZipcode()
    {
        return $this->zipcode;
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
     * Set introduction
     *
     * @param string $introduction
     * @return $this
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
        return $this;
    }

    /**
     * Get introduction
     *
     * @return string $introduction
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set conclusion
     *
     * @param string $conclusion
     * @return $this
     */
    public function setConclusion($conclusion)
    {
        $this->conclusion = $conclusion;
        return $this;
    }

    /**
     * Get conclusion
     *
     * @return string $conclusion
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }

    /**
     * Set conclusion
     *
     * @param string $conclusion
     * @return $this
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * Get information
     *
     * @return string $information
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
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

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFileHeader(File $image = null)
    {
        $this->imageFileHeader = $image;
        
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFileHeader()
    {
        return $this->imageFileHeader;
    }

    /**
     * @param string $imageHeader
     *
     * @return Product
     */
    public function setImageHeader($imageHeader)
    {
        $this->imageHeader = $imageHeader;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageHeader()
    {
        return $this->imageHeader;
    }

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile1(File $image = null)
    {
        $this->imageFile1 = $image;
        
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile1()
    {
        return $this->imageFile1;
    }

    /**
     * @param string $image1
     *
     * @return Product
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage1()
    {
        return $this->image1;
    }

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile2(File $image = null)
    {
        $this->imageFile2 = $image;
        
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile2()
    {
        return $this->imageFile2;
    }

    /**
     * @param string $image2
     *
     * @return Product
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage2()
    {
        return $this->image2;
    }
}
