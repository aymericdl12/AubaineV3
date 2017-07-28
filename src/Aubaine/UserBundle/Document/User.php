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
     * @MongoDB\Field(type="collection")
     */
    protected $placesId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sex;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $age;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $categoriesPref;

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
     * @MongoDB\Field(type="int")
     */
    protected $phone;


    public function __construct()
    {
        parent::__construct();
        $this->description = "pas de description";
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
     * Set description
     *
     * @param string $placesid
     * @return $this
     */
    public function setPlacesId($placesid)
    {
        $this->placesId = $placesid;
        return $this;
    }

    /**
     * Get placesid
     *
     * @return string $placesid
     */
    public function getPlacesId()
    {
        return $this->placesId;
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
     * Set age
     *
     * @param string $age
     * @return $this
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    /**
     * Get age
     *
     * @return string $age
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set categoriesPref
     *
     * @param string $categoriesPref
     * @return $this
     */
    public function setCategoriesPref($categoriesPref)
    {
        $this->categoriesPref = $categoriesPref;
        return $this;
    }

    /**
     * Get categoriesPref
     *
     * @return string $categoriespref
     */
    public function getCategoriesPref()
    {
        return $this->categoriesPref;
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
     * Set sex
     *
     * @param string $sex
     * @return $this
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Get sex
     *
     * @return string $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

}
