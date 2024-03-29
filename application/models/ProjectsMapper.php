<?php

/**
 * Class Application_Model_ProjectsMapper
 */
class Application_Model_ProjectsMapper extends Application_Model_DbMapper
{
    /**
     * @var DBObject
     */
    protected $dbTable;
    /**
     * @var string
     */
    protected $dbTableModel = 'Application_Model_DbTable_Projects';

    /**
     * @param Application_Model_Projects $projects
     */
    public function save(Application_Model_Projects $projects)
    {
        $data = array(
            'pid' => $projects->getPid(),
            'title' => $projects->getTitle(),
            'hashTag' => $projects->getHashTag(),
            'summary' => $projects->getSummary(),
            'description' => $projects->getDescription(),
            'useTwitter' => $projects->getUseTwitter(),
            'useInstagram' => $projects->getUseInstagram(),
            'startDateTime' => $projects->getStartDateTime(),
            'endDateTime' => $projects->getEndDateTime(),
            'status' => $projects->getStatus(),
            'trackData' => $projects->getTrackData(),
            'numOfTweets' => $projects->getNumOfTweets(),
            'numOfPosts' => $projects->getNumOfPosts(),
            'tweetFormat' => $projects->getTweetFormat(),
            'createdDateTime' => date(Tm_Constants::MySqlDateTime)
        );

        if (null === ($id = $projects->getId())) {
            unset($data['id']);
            $id = $this->getDbTable()->insert($data);
            $projects->setId($id);
        } else {
            // Do not reset createdDateTime while updating projects
            unset($data['createdDateTime']);
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
            $entries[] = new Application_Model_Projects($row->toArray());
        }
        return $entries;
    }

    /**
     * @return array
     */
    public function fetchAllByCreatedDateTimeDesc()
    {
        $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->order('createdDateTime DESC'));
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_Projects($row->toArray());
        }
        return $entries;
    }

    public function fetchAllByStatusAndCreatedDateTimeDesc($status)
    {
        $resultSet = $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('status = ?', $status)->order('createdDateTime DESC'));
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = new Application_Model_Projects($row->toArray());
        }
        return $entries;
    }

    /**
     * @param $id
     * @return Application_Model_Projects
     */
    public function fetchByID($id)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return false;
        }

        $row = $result->current();
        return new Application_Model_Projects($row->toArray());
    }

}

