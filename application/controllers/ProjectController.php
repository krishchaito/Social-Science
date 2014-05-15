<?php

/**
 * Class ProjectController
 */
class ProjectController extends Zend_Controller_Action
{

    /**
     * Initialize
     */
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * Index Action. Nothing defined for this action as of now
     */
    public function indexAction()
    {
        // No URL is defined for this action. Redirect to projects list if user comes here by any chance.
        $this->redirect('/projects');
    }

    /**
     * Create new project
     */
    public function createAction()
    {
        $hasError = false;
        $errors = array();

        if($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getParams();

            // Validate user entered data.
            list($hasError, $errors) = Tm_Validate::projectForm($postData);
            if(!$hasError) {
                $postData['hashTag'] = '#'.$postData['hashTag'];
                $postData['pid'] = md5($postData['title']);
                $postData['createdDateTime'] = date(Tm_Constants::MySqlDateTime);

                // Save the project to database.
                $projectsModel = new Application_Model_Projects($postData);
                $projectsModel->save();

                $id = $projectsModel->getId();
                if(!empty($id)) {
                    // Redirect to Success Screen
                    $this->redirect('/project/success/id/'.$id);
                    exit;
                }

                echo 'Failed to create project';
            } else {
                $this->view->title = $postData['title'];
                $this->view->hashTag = $postData['hashTag'];
                $this->view->summary = $postData['summary'];
                $this->view->description = $postData['description'];
                $this->view->useTwitter = $postData['useTwitter'];
                $this->view->useInstagram = $postData['useInstagram'];
                $this->view->usePicture = $postData['usePicture'];
                $this->view->gpsReq = $postData['gpsReq'];
                $this->view->tweetFormat = $postData['tweetFormat'];
                $this->view->trackData = unserialize($postData['trackData']);
            }
        }

        // Set URL for CANCEL action
        $this->view->onProjCancel = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        if(!empty($_SERVER['HTTP_REFERER'])) {
            $this->view->onProjCancel = $_SERVER['HTTP_REFERER'];
        }

        $this->view->hasError = $hasError;
        $this->view->errors = $errors;
    }

    /**
     * Success screen
     */
    public function successAction()
    {
        $params = $this->getRequest()->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($params['id'])) {
            $this->redirect('/projects');
        }
        $id = $params['id'];
        $project = Tm_Project::getById($id);

//        $tweetFormatArr = explode(' ', $tweetFormat);
//
//        $trackNameStr = '';
//        $trackValueStr = '';
//        $trackData = unserialize($project->getTrackData());
//        foreach ($trackData as $key => $value) {
//            $trackNameStr .= ' '.$key;
//            $trackValueStr .= ' '.$value;
//        }

        $this->view->id = $project->getId();
        $this->view->domainUrl = Tm_Project::getDomainUrl();
        $this->view->project = $project;
    }

    /**
     * project Dashboard Screen
     */
    public function viewAction()
    {
        $params = $this->getRequest()->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($params['id'])) {
            $this->redirect('/projects');
        }
        $id = $params['id'];
        $project = Tm_Project::getById($id);
        $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $id);

        $this->view->id = $project->getId();
        $this->view->project = $project;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Project Results Screen
     */
    public function resultsAction()
    {
        $params = $this->getRequest()->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($params['id'])) {
            $this->redirect('/projects');
        }
        $id = $params['id'];

        if(!empty($params['search']) && ($params['search'] == 'Search')) {
            // Calculate Start Date.
            $startDate = '';
            if(!empty($params['st_date'])) {
                $startDate = date(Tm_Constants::MySqlDateTime, strtotime($params['st_date']));
            }

            // Calculate End Date. Default - today.
            if(empty($params['end_date'])) {
                $date = date("Y-m-d");
            } else {
                $date = date("Y-m-d", strtotime($params['end_date']));
            }
            $explodedDate = explode('-', $date);
            $endDate = date(Tm_Constants::MySqlDateTime, mktime(23, 59, 59, $explodedDate[1], $explodedDate[2], $explodedDate[0]));

            $posts = Tm_Project::getPostsByProjectIdWithSearchString($id, $startDate, $endDate);
        } else {
            $posts = Tm_Project::getPostsByProjectIdWithPostCreatedAtDesc($id);
        }

        $project = Tm_Project::getById($id);
        $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $id);

        $this->view->id = $project->getId();
        $this->view->project = $project;
        $this->view->posts = $posts;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Projects Map Screen
     */
    public function mapAction()
    {
        $params = $this->getRequest()->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($params['id'])) {
            $this->redirect('/projects');
        }
        $id = $params['id'];
        $project = Tm_Project::getById($id);
        $posts = Tm_Project::getPostsByProjectIdWithPostCreatedAtDesc($id);
        $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $id);

        $this->view->id = $project->getId();
        $this->view->project = $project;
        $this->view->posts = $posts;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Edit a project
     */
    public function editAction()
    {
        $params = $this->getRequest()->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($params['id'])) {
            $this->redirect('/projects');
        }
        $id = $params['id'];
        $project = Tm_Project::getById($id);

        $nexturl = '/projects';

        if(false != $project) {
            $this->view->id = $project->getId();

            if($this->getRequest()->isPost()) {
                // Validate user entered data.
                list($hasError, $errors) = Tm_Validate::projectForm($params);
                if(!$hasError) {
                    // Save the project to database.
                    $project->setTitle($params['title']);
                    $project->setDescription($params['description']);
                    $project->setSummary($params['summary']);
                    $project->setUseTwitter($params['useTwitter']);
                    $project->setUseInstagram($params['useInstagram']);
                    $project->setUsePicture($params['usePicture']);
                    $project->setGpsReq($params['gpsReq']);
                    $project->setTweetFormat($params['tweetFormat']);
                    $project->save();

                    // Redirect
                    $this->redirect($nexturl);
                } else {
                    $this->view->title = $params['title'];
                    $this->view->hashTag = $params['hashTag'];
                    $this->view->summary = $params['summary'];
                    $this->view->description = $params['description'];
                    $this->view->useTwitter = $params['useTwitter'];
                    $this->view->useInstagram = $params['useInstagram'];
                    $this->view->usePicture = $params['usePicture'];
                    $this->view->gpsReq = $params['gpsReq'];
                    $this->view->tweetFormat = $params['tweetFormat'];
                    $this->view->trackData = unserialize($params['trackData']);

                    $this->view->hasError = $hasError;
                    $this->view->errors = $errors;
                }
            } else {
                $this->view->title = $project->getTitle();
                $this->view->hashTag = $project->getHashTag();
                $this->view->summary = $project->getSummary();
                $this->view->description = $project->getDescription();
                $this->view->useTwitter = $project->getUseTwitter();
                $this->view->useInstagram = $project->getUseInstagram();
                $this->view->usePicture = $project->getUsePicture();
                $this->view->gpsReq = $project->getGpsReq();
                $this->view->tweetFormat = $project->getTweetFormat();

                $trackData = unserialize($project->getTrackData());
                $this->view->trackData = $trackData;
                $this->view->trackCounter = count($trackData);
            }
        } else {
            $this->view->errorMsg = "Requested project doesn't exist";
        }

        // Set URL for CANCEL action
        $this->view->onProjCancel = $nexturl;
    }

    /**
     * Pulls Tweets and Posts from Twitter and Instagram and then updates database.
     */
    public function refreshAction()
    {
        $params = $this->getRequest()->getParams();

        // Return error if we do not have project id
        if(!isset($params['id'])) {
            $response = array('status' => 'failed', 'statusCode' => 404);
            echo $this->_helper->json($response);
        }
        $id = $params['id'];
        $project = Tm_Project::getById($id);

        if(!is_object($project)) {
            $response = array('status' => 'failed', 'statusCode' => 500);
            echo $this->_helper->json($response);
        }

        if($project->getUseTwitter()) {
            $twitter = new Tm_Twitter();
            $twitter->update($project);
        }

        if($project->getUseInstagram()) {
            $twitter = new Tm_Instagram();
            $twitter->update($project);
        }

        $response = array('status' => 'success', 'statusCode' => 200);
        echo $this->_helper->json($response);
    }

}