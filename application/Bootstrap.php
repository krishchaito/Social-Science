<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initPlaceholders()
    {
        // Set the DOC type
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->doctype('HTML5');

        // Set the initial title and separator:
        $view->headTitle('TweetMap')
             ->setSeparator(' - ');

        // Set StyleSheets
        $view->headLink()->prependStylesheet('/css/reset.css');
        $view->headLink()->appendStylesheet('/css/style.css');
        $view->headLink()->appendStylesheet('/css/jquery-ui.custom.min.css');

        // Set Javascript Libs
        $view->headScript()->prependFile('/js/jquery.min.js');
        $view->headScript()->appendFile('/js/jquery.nicescroll.min.js');
        $view->headScript()->appendFile('/js/jquery-ui.custom.min.js');
        $view->headScript()->appendFile('/js/main.js');
    }
}

