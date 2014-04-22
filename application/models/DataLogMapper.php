<?php

/**
 * Class Application_Model_DataLogMapper
 */
class Application_Model_DataLogMapper extends Application_Model_DbMapper
{

    /**
     * @var DBObject
     */
    protected $dbTable;
    /**
     * @var string
     */
    protected $dbTableModel = 'Application_Model_DbTable_DataLog';

    /**
     * @param Application_Model_DataLog $dataLog
     */
    public function save(Application_Model_DataLog $dataLog)
    {
        $data = array(
            'projectId' => $dataLog->getProjectId(),
            'description' => $dataLog->getDescription(),
            'field1' => $dataLog->getField1(),
            'field2' => $dataLog->getField2(),
            'field3' => $dataLog->getField3(),
            'field4' => $dataLog->getField4(),
            'field5' => $dataLog->getField5(),
            'transport' => $dataLog->getTransport(),
            'origin' => $dataLog->getOrigin(),
            'remoteHost' => $dataLog->getRemoteHost(),
            'requestValues' => $dataLog->getRequestValues(),
            'responseValues' => $dataLog->getResponseValues(),
            'status' => $dataLog->getStatus(),
            'statusCode' => $dataLog->getStatusCode(),
            'communicationDateTime' => $dataLog->getCommunicationDateTime()
        );

        if (null === ($id = $dataLog->getId())) {
            unset($data['id']);
            $insertId = $this->getDbTable()->insert($data);
            $dataLog->setId($insertId);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
}

