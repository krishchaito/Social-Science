<?php

/**
 * Class Application_Model_PostsMetaMapper
 */
class Application_Model_PostsMetaMapper extends Application_Model_DbMapper
{

    /**
     * @var DBObject
     */
    protected $dbTable;
    /**
     * @var string
     */
    protected $dbTableModel = 'Application_Model_DbTable_PostsMeta';

    /**
     * @param Application_Model_PostsMeta $postsMeta
     */
    public function save(Application_Model_PostsMeta $postsMeta)
    {
        $data = array(
            'projectId' => $postsMeta->getProjectId(),
            'metaKey' => $postsMeta->getMetaKey(),
            'metaValue' => $postsMeta->getMetaValue()
        );

        if (null === ($id = $postsMeta->getId())) {
            unset($data['id']);
            $insertId = $this->getDbTable()->insert($data);
            $postsMeta->setId($insertId);
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
            $entries[] = new Application_Model_PostsMeta($row->toArray());
        }
        return $entries;
    }

    /**
     * @param $metaKey
     * @param $projectId
     * @return Application_Model_PostsMeta
     */
    public function fetchByMetaKeyDesc($metaKey, $projectId)
    {
        $result = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('metaKey = ?', $metaKey)->where('projectId = ?', $projectId)->order('id DESC'));
        if (0 == count($result)) {
            return;
        }

        return new Application_Model_PostsMeta($result->toArray());
    }

}

