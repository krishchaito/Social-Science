<?php

class Tm_Session_Create extends Tm_Session_Abstract
{
    static public function NewSession($sessionNameSpace, $data = array())
    {
        $tmSession = new Zend_Session_Namespace($sessionNameSpace);
        $tmSession->session = '';
        
        // Regenerate the session id just to be sure
        Zend_Session::regenerateId();
        // Clear Zend_Auth identity
        Zend_Auth::getInstance()->clearIdentity();
        
        $session = new Tm_Session();
        $session->setTimeZone($data['timeZone']);

        $tmSession->session = $session;
        return $session;
    }
}