<?php

class ProjectsController extends Tm_BaseController
{

    public function init()
    {
        /* Initialize action controller here */
    }

    // Displays list of all the projects
    public function indexAction()
    {
        $projectsMapper = new Application_Model_ProjectsMapper();
        $this->view->projects = $projectsMapper->fetchAll();
    }

}



