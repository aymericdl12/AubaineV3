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
    protected $placeId;
    
    /**
     * @MongoDB\Field(type="collection")
     */
    protected $placesId;

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

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursMonday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursTuesday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursWednesday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursThursday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursFriday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursSaturday;

    /**
     * @MongoDB\Field(type="string")
     */
    private $hoursSunday;

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
     * @param string $placeid
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
     * @return string $placeid
     */
    public function getPlaceId()
    {
        return $this->placeId;
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
    

    /**
     * Set hoursMonday
     *
     * @param string $hoursMonday
     * @return $this
     */
    public function setHoursMonday($hoursMonday)
    {
        $this->hoursMonday = $hoursMonday;
        return $this;
    }

    /**
     * Get hoursMonday
     *
     * @return string $hoursMonday
     */
    public function getHoursMonday()
    {
        return $this->hoursMonday;
    }

    /**
     * Set hoursTuesday
     *
     * @param string $hoursTuesday
     * @return $this
     */
    public function setHoursTuesday($hoursTuesday)
    {
        $this->hoursTuesday = $hoursTuesday;
        return $this;
    }

    /**
     * Get hoursTuesday
     *
     * @return string $hoursTuesday
     */
    public function getHoursTuesday()
    {
        return $this->hoursTuesday;
    }

    /**
     * Set hoursWednesday
     *
     * @param string $hoursWednesday
     * @return $this
     */
    public function setHoursWednesday($hoursWednesday)
    {
        $this->hoursWednesday = $hoursWednesday;
        return $this;
    }

    /**
     * Get hoursWednesday
     *
     * @return string $hoursWednesday
     */
    public function getHoursWednesday()
    {
        return $this->hoursWednesday;
    }

    /**
     * Set hoursThursday
     *
     * @param string $hoursThursday
     * @return $this
     */
    public function setHoursThursday($hoursThursday)
    {
        $this->hoursThursday = $hoursThursday;
        return $this;
    }

    /**
     * Get hoursThursday
     *
     * @return string $hoursThursday
     */
    public function getHoursThursday()
    {
        return $this->hoursThursday;
    }

    /**
     * Set hoursFriday
     *
     * @param string $hoursFriday
     * @return $this
     */
    public function setHoursFriday($hoursFriday)
    {
        $this->hoursFriday = $hoursFriday;
        return $this;
    }

    /**
     * Get hoursFriday
     *
     * @return string $hoursFriday
     */
    public function getHoursFriday()
    {
        return $this->hoursFriday;
    }

    /**
     * Set hoursSaturday
     *
     * @param string $hoursSaturday
     * @return $this
     */
    public function setHoursSaturday($hoursSaturday)
    {
        $this->hoursSaturday = $hoursSaturday;
        return $this;
    }

    /**
     * Get hoursSaturday
     *
     * @return string $hoursSaturday
     */
    public function getHoursSaturday()
    {
        return $this->hoursSaturday;
    }

    /**
     * Set hoursSunday
     *
     * @param string $hoursSunday
     * @return $this
     */
    public function setHoursSunday($hoursSunday)
    {
        $this->hoursSunday = $hoursSunday;
        return $this;
    }

    /**
     * Get hoursSunday
     *
     * @return string $hoursSunday
     */
    public function getHoursSunday()
    {
        return $this->hoursSunday;
    }
}
