<?php

class Application_Model_PostsMeta extends Application_Model_Db
{

    protected $_id;
    protected $_projectId = 0;
    protected $_metaKey = '';
    protected $_metaValue = '';

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
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
     * @param string $metaKey
     */
    public function setMetaKey($metaKey)
    {
        $this->_metaKey = $metaKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaKey()
    {
        return $this->_metaKey;
    }

    /**
     * @param string $metaValue
     */
    public function setMetaValue($metaValue)
    {
        $this->_metaValue = $metaValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetaValue()
    {
        return $this->_metaValue;
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
     * Insert or Update record to database
     */
    public function save()
    {
        $postsMetaMapper = new Application_Model_PostsMetaMapper();
        $postsMetaMapper->save($this);
    }

}

