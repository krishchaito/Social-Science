<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Year: 2014
 */

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Get application environment
$acceptedEnvironments = array(
    'development',
    'testing',
    'staging',
    'production'
);

$environment = '';
// First argument should be Application Environment
if(isset($argv[1])) {
    $environment = $argv[1];
    if(!in_array($environment, $acceptedEnvironments)) {
        die('Invalid environment ' . $environment . ' given as the first argument');
    }
}

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', ($environment ? $environment : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path()
)));

// Set the default timezone
date_default_timezone_set('UTC');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.json'
);

// Initialize application
$application->bootstrap(array('db'));