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
     * @MongoDB\Field(type="boolean")
     */
    protected $professional=False;
    
    /**
     * @MongoDB\Field(type="collection")
     */
    protected $placesId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $lastname;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $birthdate;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sex;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $category;
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

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $preferences;

    /**
     * @MongoDB\Field(type="boolean")
     */
    protected $activated;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $deadline;

    /**
     * @MongoDB\Field(type="string")
     */
    private $facebookid;
    
    /**
     * @MongoDB\Field(type="string")
     */
    private $facebookAccessToken;


    public function __construct()
    {
        parent::__construct();
        $this->professional = False;
        $this->activated = False;
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
     * Add placesid
     *
     * @return string $placesid
     */
    public function addPlaceId($placeId)
    {
        if(count($this->placesId)>0){
            array_push($this->placesId, $placeId);
        }
        else{
            $this->placesId= [$placeId];
        }

        return $this;
    }

    /**
     * Set preferences
     *
     * @param string $preferences
     * @return $this
     */
    public function setPreferences($preferences)
    {
        $this->preferences = $preferences;
        return $this;
    }

    /**
     * Get preferences
     *
     * @return string $preferences
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

    /**
     * Add preferences
     *
     * @return string $preferences
     */
    public function addPreference($preference)
    {
        if(count($this->preferences)>0){
            if (($key = array_search($preference, $this->preferences)) === false) {
                array_push($this->preferences, $preference);
            }            
        }
        else{
            $this->preferences= [$preference];
        }

        return $this;
    }

    /**
     * Add preferences
     *
     * @return string $preferences
     */
    public function removePreference($preference)
    {

        if(count($this->preferences)>0){
            if (($key = array_search($preference, $this->preferences)) !== false) {
                unset($this->preferences[$key]);
            }
        }

        return $this;
    }

    /**
     * Set activated
     *
     * @param string $activated
     * @return $this
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
        if($activated===True){
            $this->deadline = new \Datetime("+ 1 year");
        }
        return $this;
    }

    /**
     * Get activated
     *
     * @return string $activated
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set deadline
     *
     * @param string $deadline
     * @return $this
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
        return $this;
    }

    /**
     * Get deadline
     *
     * @return string $deadline
     */
    public function getDeadline()
    {
        return $this->deadline;
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
     * Set birthdate
     *
     * @param int $birthdate
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return int $birthdate
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set professional
     *
     * @param int $professional
     * @return $this
     */
    public function setProfessional($professional)
    {
        $this->professional = $professional;
        return $this;
    }

    /**
     * Get professional
     *
     * @return int $professional
     */
    public function getProfessional()
    {
        return $this->professional;
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
     * Set lastname
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
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
    /**
     * @param string $facebookid
     * @return User
     */
    public function setfacebookid($facebookid)
    {
        $this->facebookid = $facebookid;

        return $this;
    }

    /**
     * @return string
     */
    public function getfacebookid()
    {
        return $this->facebookid;
    }

    /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }
}
