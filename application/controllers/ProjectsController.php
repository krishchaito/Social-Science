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
        $projects = $projectsMapper->fetchAll();

        $lastUpdatedOn = array();
        foreach($projects as $project) {
            $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $project->getId());
            $lastUpdatedOn[$project->getId()] = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
        }

        $this->view->projects = $projects;
        $this->view->lastUpdatedOn = $lastUpdatedOn;
    }

}



