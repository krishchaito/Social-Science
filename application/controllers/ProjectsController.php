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
        $session = $this->getSession();
        $timeZone = $session->getTimeZone();

        $projectsMapper = new Application_Model_ProjectsMapper();
        $projects = $projectsMapper->fetchAllByCreatedDateTimeDesc();

        $lastUpdatedOn = array();
        $projectsJSON = array();

        foreach($projects as $project) {
            $projectArr = $project->toArray();
            $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $project->getId());

            if(is_object($postsMetaLastTweetUpdatedOn)) {
                $lastUpdatedDateStr = strtotime($postsMetaLastTweetUpdatedOn->getMetaValue());
                $projectArr['_lastUpdatedOn'] = date(Tm_Constants::HUMAN_READABLE_DATETIME, $lastUpdatedDateStr + $timeZone * 60);
            } else {
                $projectArr['_lastUpdatedOn'] = 'NA';
            }

            array_push($projectsJSON, $projectArr);
        }

        $this->view->projects = json_encode($projectsJSON);
    }
}