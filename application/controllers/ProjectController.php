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
            list($hasError, $errors) = Tm_Validate::newProjectForm($postData);
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

        $tweetFormat = $project->getTweetFormat();
//        $tweetFormatArr = explode(' ', $tweetFormat);
//
//        $trackNameStr = '';
//        $trackValueStr = '';
//        $trackData = unserialize($project->getTrackData());
//        foreach ($trackData as $key => $value) {
//            $trackNameStr .= ' '.$key;
//            $trackValueStr .= ' '.$value;
//        }

        $this->view->id = $id;
        $this->view->domainUrl = Tm_Project::getDomainUrl();
        $this->view->project = $project;
        $this->view->tweetFormat = $tweetFormat;
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

        $this->view->id = $id;
        $this->view->project = $project;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Project Results Screen
     */
    public function resultsAction()
    {
        $request = $this->getRequest();
        $data = $request->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($data['id'])) {
            $this->redirect('/projects');
        }
        $id = $data['id'];
        $project = Tm_Project::getById($id);
        $posts = Tm_Project::getPostsByProjectIdWithPostCreatedAtDesc($id);
        $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $id);

        $this->view->id = $id;
        $this->view->project = $project;
        $this->view->posts = $posts;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Projects Map Screen
     */
    public function mapAction()
    {
        $request = $this->getRequest();
        $data = $request->getParams();

        // Redirect to projects list if we do not have project id
        if(!isset($data['id'])) {
            $this->redirect('/projects');
        }
        $id = $data['id'];
        $project = Tm_Project::getById($id);
        $posts = Tm_Project::getPostsByProjectIdWithPostCreatedAtDesc($id);
        $postsMetaLastTweetUpdatedOn = Tm_Project::getPostsByMetaKeyDesc(Tm_Constants::lASTUPDATEDON_METAKEY, $id);

        $this->view->id = $id;
        $this->view->project = $project;
        $this->view->posts = $posts;
        $this->view->postsMetaLastUpdatedOn = (is_object($postsMetaLastTweetUpdatedOn)) ? $postsMetaLastTweetUpdatedOn->getMetaValue() : 'NA';
    }

    /**
     * Pulls Tweets and Posts from Twitter and Instagram and then updates database.
     */
    public function refreshAction()
    {
        $request = $this->getRequest();
        $data = $request->getParams();

        // Return error if we do not have project id
        if(!isset($data['id'])) {
            $data = array('status' => 'failed', 'statusCode' => 404);
            echo $this->_helper->json($data);
        }
        $id = $data['id'];
        $project = Tm_Project::getById($id);

        if(!is_object($project)) {
            $data = array('status' => 'failed', 'statusCode' => 500);
            echo $this->_helper->json($data);
        }

        if($project->getUseTwitter()) {
            $twitter = new Tm_Twitter();
            $twitter->update($project);
        }

        if($project->getUseInstagram()) {
            $twitter = new Tm_Instagram();
            $twitter->update($project);
        }

        $data = array('status' => 'success', 'statusCode' => 200);
        echo $this->_helper->json($data);
    }

}