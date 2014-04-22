<?php

/**
 * Class Application_Model_DataLog
 */
class Application_Model_DataLog extends Application_Model_Db
{
    protected $_id;
    protected $_projectId = 0;
    protected $_description = '';
    protected $_field1 = '';
    protected $_field2 = '';
    protected $_field3 = '';
    protected $_field4 = '';
    protected $_field5 = '';
    protected $_transport = '';
    protected $_origin = 'Us';
    protected $_remoteHost = '';
    protected $_requestValues = '';
    protected $_responseValues = '';
    protected $_status = 'Succeeded';
    protected $_statusCode = '';
    protected $_communicationDateTime = '0000-00-00 00:00:00';
    protected $_tsLastUpdated = '';

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @param string $communicationDateTime
     */
    public function setCommunicationDateTime($communicationDateTime)
    {
        $this->_communicationDateTime = $communicationDateTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommunicationDateTime()
    {
        return $this->_communicationDateTime;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $field1
     */
    public function setField1($field1)
    {
        $this->_field1 = $field1;
        return $this;
    }

    /**
     * @return string
     */
    public function getField1()
    {
        return $this->_field1;
    }

    /**
     * @param string $field2
     */
    public function setField2($field2)
    {
        $this->_field2 = $field2;
        return $this;
    }

    /**
     * @return string
     */
    public function getField2()
    {
        return $this->_field2;
    }

    /**
     * @param string $field3
     */
    public function setField3($field3)
    {
        $this->_field3 = $field3;
        return $this;
    }

    /**
     * @return string
     */
    public function getField3()
    {
        return $this->_field3;
    }

    /**
     * @param string $field4
     */
    public function setField4($field4)
    {
        $this->_field4 = $field4;
        return $this;
    }

    /**
     * @return string
     */
    public function getField4()
    {
        return $this->_field4;
    }

    /**
     * @param string $field5
     */
    public function setField5($field5)
    {
        $this->_field5 = $field5;
        return $this;
    }

    /**
     * @return string
     */
    public function getField5()
    {
        return $this->_field5;
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
     * @param string $origin
     */
    public function setOrigin($origin)
    {
        $this->_origin = $origin;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->_origin;
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
     * @param string $remoteHost
     */
    public function setRemoteHost($remoteHost)
    {
        $this->_remoteHost = $remoteHost;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemoteHost()
    {
        return $this->_remoteHost;
    }

    /**
     * @param string $requestValues
     */
    public function setRequestValues($requestValues)
    {
        $this->_requestValues = $requestValues;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestValues()
    {
        return $this->_requestValues;
    }

    /**
     * @param string $responseValues
     */
    public function setResponseValues($responseValues)
    {
        $this->_responseValues = $responseValues;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseValues()
    {
        return $this->_responseValues;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->_statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * @param string $transport
     */
    public function setTransport($transport)
    {
        $this->_transport = $transport;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransport()
    {
        return $this->_transport;
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
     * Insert or Update record to database
     */
    public function save()
    {
        $dataLogMapper = new Application_Model_DataLogMapper();
        $dataLogMapper->save($this);
    }

}

