<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Year: 2014
 */

if(Zend_Session::isStarted()) {
    trigger_error('Session was already started.');
    return false;
}

Zend_Session::start();