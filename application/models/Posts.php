<?php

/**
 * Class Application_Model_Posts
 */
class Application_Model_Posts extends Application_Model_Db
{

    protected $_id;
    protected $_projectId = 0;
    protected $_postIdStr = '';
    protected $_text = '';
    protected $_userIdStr = '';
    protected $_username = '';
    protected $_userScreenName = '';
    protected $_source = 'Twitter';
    protected $_postCreatedAt = '0000-00-00 00:00:00';
    protected $_coordinatesLongitude = '';
    protected $_coordinatesLatitude = '';
    protected $_place = '';
    protected $_countryCode = '';
    protected $_imageURL = '';
    protected $_imageThumbnail = '';
    protected $_lowResolutionImage = '';
    protected $_createdDateTime = '0000-00-00 00:00:00';
    protected $_tsLastUpdated = '';

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param int $projectId
     */
    public function setProjectId($projectId)
    {
        $this->_projectId = $projectId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->_projectId;
    }

    /**
     * @param string $coordinatesLatitude
     */
    public function setCoordinatesLatitude($coordinatesLatitude)
    {
        $this->_coordinatesLatitude = $coordinatesLatitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinatesLatitude()
    {
        return $this->_coordinatesLatitude;
    }

    /**
     * @param string $coordinatesLongitude
     */
    public function setCoordinatesLongitude($coordinatesLongitude)
    {
        $this->_coordinatesLongitude = $coordinatesLongitude;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinatesLongitude()
    {
        return $this->_coordinatesLongitude;
    }

    /**
     * @param string $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->_createdDateTime = $createdDateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedDateTime()
    {
        return $this->_createdDateTime;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $imageURL
     */
    public function setImageURL($imageURL)
    {
        $this->_imageURL = $imageURL;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageURL()
    {
        return $this->_imageURL;
    }

    /**
     * @param string $postCreatedAt
     */
    public function setPostCreatedAt($postCreatedAt)
    {
        $this->_postCreatedAt = $postCreatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCreatedAt()
    {
        return $this->_postCreatedAt;
    }

    /**
     * @param string $postIdStr
     */
    public function setPostIdStr($postIdStr)
    {
        $this->_postIdStr = $postIdStr;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostIdStr()
    {
        return $this->_postIdStr;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->_source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param string $tsLastUpdated
     */
    public function setTsLastUpdated($tsLastUpdated)
    {
        $this->_tsLastUpdated = $tsLastUpdated;
        return $this;
    }

    /**
     * @return string
     */
    public function getTsLastUpdated()
    {
        return $this->_tsLastUpdated;
    }

    /**
     * @param string $userIdStr
     */
    public function setUserIdStr($userIdStr)
    {
        $this->_userIdStr = $userIdStr;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdStr()
    {
        return $this->_userIdStr;
    }

    /**
     * @param string $userScreenName
     */
    public function setUserScreenName($userScreenName)
    {
        $this->_userScreenName = $userScreenName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserScreenName()
    {
        return $this->_userScreenName;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->_countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->_countryCode;
    }

    /**
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->_place = $place;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->_place;
    }

    /**
     * @param string $imageThumbnail
     */
    public function setImageThumbnail($imageThumbnail)
    {
        $this->_imageThumbnail = $imageThumbnail;
    }

    /**
     * @return string
     */
    public function getImageThumbnail()
    {
        return $this->_imageThumbnail;
    }

    /**
     * @param string $lowResolutionImage
     */
    public function setLowResolutionImage($lowResolutionImage)
    {
        $this->_lowResolutionImage = $lowResolutionImage;
    }

    /**
     * @return string
     */
    public function getLowResolutionImage()
    {
        return $this->_lowResolutionImage;
    }

    /**
     * Insert or Update record to database
     */
    public function save()
    {
        $postMapper = new Application_Model_PostsMapper();
        $postMapper->save($this);
    }

}

