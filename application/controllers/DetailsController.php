<?php

class DetailsController extends Tm_BaseController
{
    public function preDispatch()
    {
        // Disable layout
        $this->_helper->layout->disableLayout();
    }

    public function indexAction()
    {

    }

    public function createAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $data['timeZone'] = $this->getRequest()->getParam('tz', 0);

        $oldSession = $this->getSession();
        $nextUrl = $oldSession->nextUrl;
        if(empty($nextUrl)) {
            $nextUrl = Tm_Atrizine::GetRequestScheme() . '://' . $_SERVER['HTTP_HOST'];
        }

        // destroy old session
        Zend_Session::namespaceUnset(Tm_Session::ATRAZINE_WEBAPP);

        // Create new session
        $session = Tm_Session_Create::NewSession(Tm_Session::ATRAZINE_WEBAPP, $data);
        if (!$session instanceof Tm_Session) {
            trigger_error('Failed to create session.');
        }

        $this->redirect($nextUrl);
    }
}