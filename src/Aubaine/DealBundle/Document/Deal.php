<?php

namespace Aubaine\DealBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @MongoDB\Document(repositoryClass="Aubaine\DealBundle\Repository\DealRepository")
 * @Vich\Uploadable
 */
class Deal
{
    /**
     * @MongoDB\Id
     */
    protected $id;

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
     * @Assert\Length(min=10)
     */
    protected $intro;

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
    protected $text1;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $date;    

    /**
     * 
     * @Vich\UploadableField(mapping="deal_imageHeader", fileNameProperty="imageHeader")
     */
    private $imageFileHeader;

    /**
     * @MongoDB\Field(type="string")
     */
    private $imageHeader;


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
     * Set intro
     *
     * @param string $intro
     * @return $this
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
        return $this;
    }

    /**
     * Get intro
     *
     * @return string $intro
     */
    public function getIntro()
    {
        return $this->intro;
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
     * Set text1
     *
     * @param string $text1
     * @return $this
     */
    public function setText1($text1)
    {
        $this->text1 = $text1;
        return $this;
    }

    /**
     * Get text1
     *
     * @return string $text1
     */
    public function getText1()
    {
        return $this->text1;
    }

    /**
     * Set text2
     *
     * @param string $text2
     * @return $this
     */
    public function setText2($text2)
    {
        $this->text2 = $text2;
        return $this;
    }

    /**
     * Get text2
     *
     * @return string $text2
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * Set content
     *
     * @param string $message
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
