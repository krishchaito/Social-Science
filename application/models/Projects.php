<?php

/**
 * Class Application_Model_Projects
 */
class Application_Model_Projects extends Application_Model_Db
{
    protected $_id;
    protected $_pid = 0;
    protected $_title = '';
    protected $_hashTag = '';
    protected $_summary = '';
    protected $_description = '';
    protected $_useTwitter = 0;
    protected $_useInstagram = 0;
    protected $_startDateTime = '0000-00-00 00:00:00';
    protected $_endDateTime = '0000-00-00 00:00:00';
    protected $_tweetFormat = '';
    protected $_trackData = '';
    protected $_numOfTweets = 0;
    protected $_numOfPosts = 0;
    protected $_status = self::STATUS_ACTIVE;
    protected $_createdDateTime = '0000-00-00 00:00:00';
    protected $_tsLastUpdated = '';

    const STATUS_ACTIVE = 'Active';
    const STATUS_CLOSED = 'Closed';

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param mixed $createdDateTime
     */
    public function setCreatedDateTime($createdDateTime)
    {
        $this->_createdDateTime = $createdDateTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedDateTime()
    {
        return $this->_createdDateTime;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $hashTag
     */
    public function setHashTag($hashTag)
    {
        $this->_hashTag = $hashTag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHashTag()
    {
        return $this->_hashTag;
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
     * @param mixed $pid
     */
    public function setPid($pid)
    {
        $this->_pid = $pid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPid()
    {
        return $this->_pid;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->_summary = $summary;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->_summary;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $tsLastUpdated
     */
    public function setTsLastUpdated($tsLastUpdated)
    {
        $this->_tsLastUpdated = $tsLastUpdated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTsLastUpdated()
    {
        return $this->_tsLastUpdated;
    }

    /**
     * @param mixed $tweetFormat
     */
    public function setTweetFormat($tweetFormat)
    {
        $this->_tweetFormat = $tweetFormat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTweetFormat()
    {
        return $this->_tweetFormat;
    }

    /**
     * @param mixed $useInstagram
     */
    public function setUseInstagram($useInstagram)
    {
        $this->_useInstagram = $useInstagram;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUseInstagram()
    {
        return $this->_useInstagram;
    }

    /**
     * @param mixed $useTwitter
     */
    public function setUseTwitter($useTwitter)
    {
        $this->_useTwitter = $useTwitter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUseTwitter()
    {
        return $this->_useTwitter;
    }

    /**
     * @param mixed $useTwitter
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $track
     */
    public function setTrackData($trackData)
    {
        $this->_trackData = $trackData;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackData()
    {
        return $this->_trackData;
    }

    /**
     * @param int $numOfPosts
     */
    public function setNumOfPosts($numOfPosts)
    {
        $this->_numOfPosts = $numOfPosts;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumOfPosts()
    {
        return $this->_numOfPosts;
    }

    /**
     * @param int $numOfTweets
     */
    public function setNumOfTweets($numOfTweets)
    {
        $this->_numOfTweets = $numOfTweets;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumOfTweets()
    {
        return $this->_numOfTweets;
    }

    /**
     * @param string $endDate
     */
    public function setEndDateTime($endDateTime)
    {
        $this->_endDateTime = $endDateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDateTime()
    {
        return $this->_endDateTime;
    }

    /**
     * @param string $startDate
     */
    public function setStartDateTime($startDateTime)
    {
        $this->_startDateTime = $startDateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDateTime()
    {
        return $this->_startDateTime;
    }

    public function save()
    {
        $projectsMapper = new Application_Model_ProjectsMapper();
        $projectsMapper->save($this);
    }

}

