<?php

/**
 * Class Application_Model_PostsMapper
 */
class Application_Model_PostsMapper extends Application_Model_DbMapper
{

    /**
     * @var DBObject
     */
    protected $dbTable;
    /**
     * @var string
     */
    protected $dbTableModel = 'Application_Model_DbTable_Posts';

    /**
     * @param Application_Model_Posts $posts
     */
    public function save(Application_Model_Posts $posts)
    {
        $data = array(
            'projectId' => $posts->getProjectId(),
            'postIdStr' => $posts->getPostIdStr(),
            'text' => $posts->getText(),
            'userIdStr' => $posts->getUserIdStr(),
            'username' => $posts->getUsername(),
            'userScreenName' => $posts->getUserScreenName(),
            'source' => $posts->getSource(),
            'postCreatedAt' => $posts->getPostCreatedAt(),
            'coordinatesLongitude' => $posts->getCoordinatesLongitude(),
            'coordinatesLatitude' => $posts->getCoordinatesLatitude(),
            'place' => $posts->getPlace(),
            'countryCode' => $posts->getCountryCode(),
            'imageURL' => $posts->getImageURL(),
            'imageThumbnail' => $posts->getImageThumbnail(),
            'lowResolutionImage' => $posts->getLowResolutionImage(),
            'createdDateTime' => $posts->getCreatedDateTime()
        );

        if (null === ($id = $posts->getId())) {
            unset($data['id']);
            $insertId = $this->getDbTable()->insert($data);
            $posts->setId($insertId);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_Posts($row->toArray());
        }
        return $entries;
    }

    /**
     * @param $projectId
     * @return array
     */
    public function fetchAllByProjectIDWithPostCreatedAtDesc($projectId)
    {
        $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('projectId = ?', $projectId)->order('postCreatedAt DESC'));
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_Posts($row->toArray());
        }
        return $entries;
    }

    /**
     * @param $id
     * @return Application_Model_Posts
     */
    public function fetchByPostIdStrAndProjectId($postIdStr, $projectId)
    {
        $result = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('postIdStr = ?', $postIdStr)->where('projectId = ?', $projectId));
        if (0 == count($result)) {
            return;
        }

        return new Application_Model_Posts($result->toArray());
    }

    public function fetchAllByProjectIDBetweenDates($projectId, $startDate, $endDate)
    {
        if(empty($startDate)) {
            $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('projectId = ?', $projectId)->where('postCreatedAt < ?', $endDate)->order('postCreatedAt DESC'));
        } else {
            $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('projectId = ?', $projectId)->where('postCreatedAt > ?', $startDate)->where('postCreatedAt < ?', $endDate)->order('postCreatedAt DESC'));
        }

        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_Posts($row->toArray());
        }
        return $entries;
    }
}

