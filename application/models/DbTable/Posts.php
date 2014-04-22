<?php

/**
 * Class Application_Model_DbTable_Posts
 */
class Application_Model_DbTable_Posts extends Zend_Db_Table_Abstract
{

    /**
     * @var string
     */
    protected $_name = 'posts';
    /**
     * @var string
     */
    protected $_primary = 'id';

}

