<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Date: 4/17/14
 * Time: 3:36 PM
 */

/**
 * Class Application_Model_DbMapper
 */
class Application_Model_DbMapper
{

    /**
     * @param $dbTable
     * @return $this
     * @throws Exception
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->dbTable = $dbTable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDbTable()
    {
        if (null === $this->dbTable) {
            $this->setDbTable($this->dbTableModel);
        }
        return $this->dbTable;
    }

}

