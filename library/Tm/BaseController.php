<?php

class Tm_BaseController extends Zend_Controller_Action
{
    protected $mSessionNameSpace = Tm_Session::ATRAZINE_WEBAPP;

    public function preDispatch()
    {
        $session = $this->getSession();

        $timeZone = $session->getTimeZone();
        if(empty($timeZone)) {
            $cookieTimeZone = $this->getRequest()->getCookie('tz', 0);
            $session->setTimeZone($cookieTimeZone);
        }
    }

    public function getSession()
    {
        try{
            $TmSession = new Zend_Session_Namespace($this->mSessionNameSpace);
        } catch (Zend_Session_Exception $e) {
            trigger_error('GetSession: Cannot instantiate session');
        }

        if('details' == $this->getRequest()->getControllerName()) {
            return $TmSession;
        }

        // Redirect and get user time zone if we do not have user session.
        if (!$TmSession->session instanceof Tm_Session) {
            $TmSession->nextUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->redirect('/details');
        }

        // Pull the session
        return $TmSession->session;
    }
}