<?php

namespace Aubaine\UserBundle\Document;

use Symfony\Component\Security\Core\User\UserInterface;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @MongoDB\Document
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $addressDisplayed;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $zipcode;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * 
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @MongoDB\Field(type="string")
     */
    private $imageName;

    /**
     * @MongoDB\Field(type="float")
     * @Assert\NotNull()
     */
    protected $lati;

    /**
     * @MongoDB\Field(type="float")
     * @Assert\NotNull()
     */
    protected $longi;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $phone;

    public function __construct()
    {
        parent::__construct();
        $this->description = "pas de description";
        // your own logic
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }
    
    /**
     * @param integer $imageSize
     *
     * @return Product
     */
    public function setImageSize($imageSize)
    {
        $this->imagesize = $imageSize;
        
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getImageSize()
    {
        return $this->imageSize;
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
     * Set addressDisplayed
     *
     * @param string $addressDisplayed
     * @return $this
     */
    public function setAddressDisplayed($addressDisplayed)
    {
        $this->addressDisplayed = $addressDisplayed;
        return $this;
    }

    /**
     * Get addressDisplayed
     *
     * @return string $addressDisplayed
     */
    public function getAddressDisplayed()
    {
        return $this->addressDisplayed;
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
}
