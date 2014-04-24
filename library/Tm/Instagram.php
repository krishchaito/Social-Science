<?php
/**
 * Created by PhpStorm.
 * User: Vinay
 */

/**
 * Class Tm_Instagram
 */
class Tm_Instagram
{
    protected $maxSimultaneousRequests = 3;
    protected $totalResultsCount = 0;
    protected $savedResultsCount = 0;
    protected $sinceId = 0;
    protected $lastPostCreatedAt;
    
    /**
     * @var Object Application_Model_Projects
     */
    protected $project = '';

    protected $apiUrl = 'https://api.instagram.com/v1';
    protected $clientId = '98766d2483ef46c79bf4a51f4044c5cf';

    // Delete this
    protected $consumerKey = '5mKBk6c6w4mqpbnHZCcByw';
    protected $consumerSecret = 'ffhuHwM4fwTUe6nV6cqbOYUenQd34CvGZ0wtr12SA';
    protected $accessToken = '141138652-dgwtDK0uUqciyylEBACgLvd8LMADLvQDENIMa2Bx';
    protected $accessTokenSecret = 'bKOSzvZnVmXGJqQc3AeAjYxJSUB6U2bI1dYJO0ZSzJsoZ';
    // Delete above

    function __construct()
    {

    }

    public function update(Application_Model_Projects &$project)
    {
        $this->project = $project;

        for($requestNo = 0; $requestNo < $this->maxSimultaneousRequests; $requestNo++) {
            if((empty($this->totalResultsCount) && $requestNo < 1) || ($this->totalResultsCount == 20)) {
                $this->updatePosts();
            }
        }
    }

    public function updatePosts()
    {
        $postsMetaMapper = new Application_Model_PostsMetaMapper();
        $minIdStr = $postsMetaMapper->fetchByMetaKeyDesc(Tm_Constants::INSTAGRAMMINIDSTR_METAKEY, $this->project->getId());

        $this->lastPostCreatedAt = 0;
        $this->savedResultsCount = 0;

        $tag = substr($this->project->getHashTag(), 1);
        $searchUri = '/tags/'.$tag.'/media/recent';
        $params = array('client_id' => $this->clientId);
        if(is_object($minIdStr)) {
            $params['min_tag_id'] = $minIdStr->getMetaValue();
        }

        $url = $this->apiUrl . $searchUri . '?' . http_build_query($params);
        $results = $this->requestInstagramApi($url);

        // Save communication to dataLog
        $this->saveCommunicationToLog($results, $url);

        // Check and Save all results in DB.
        if(200 == $results->meta->code) {
            foreach($results->data as $post) {
                // Check if we already have this post
                $postsMapper = new Application_Model_PostsMapper();
                $explodedData = explode("_", $post->id);
                $result = $postsMapper->fetchByPostIdStr($explodedData[0]);

                if(!is_object($result)) {
                    $this->savePost($post);
                    $this->savedResultsCount++;

                    if(empty($this->lastPostCreatedAt)) {
                        $this->lastPostCreatedAt = date('Y-m-d H:i:s', $post->created_time);
                    }
                }
            }

            $this->totalResultsCount = count($results->data);
            $this->saveMetaData($results->pagination);
            $this->saveProject();
        }
    }

    protected function saveProject()
    {
        $numOfPosts = $this->project->getNumOfPosts() + $this->savedResultsCount;
        $this->project->setNumOfPosts($numOfPosts);
        $this->project->save();
    }

    public function savePost($post)
    {
        $explodedData = explode("_", $post->id);
        $postId = $explodedData[0];
        $userId = $explodedData[1];

        $postDb = new Application_Model_Posts();
        $postDb->setProjectId($this->project->getId());
        $postDb->setPostIdStr($postId);
        $postDb->setText($this->gettext($post));
        $postDb->setUserIdStr($userId);
        $postDb->setUsername($post->user->full_name);
        $postDb->setUserScreenName($post->user->username);
        $postDb->setSource(Tm_Constants::INSTAGRAM);
        $postDb->setPostCreatedAt(date('Y-m-d H:i:s', $post->created_time));

        $coordinates = $this->getCoordinates($post);
        if(!empty($coordinates)) {
            $postDb->setCoordinatesLongitude($coordinates[0]);
            $postDb->setCoordinatesLatitude($coordinates[1]);
        }
        $postDb->setPlace($this->getPlace($post));

        $imageUrls = $this->getImageUrl($post);
        if(!empty($imageUrls)) {
            $postDb->setImageURL($imageUrls['standard_resolution']);
            $postDb->setImageThumbnail($imageUrls['thumbnail']);
            $postDb->setLowResolutionImage($imageUrls['low_resolution']);
        }
        $postDb->setCreatedDateTime(date(Tm_Constants::MySqlDateTime));
        $postDb->save();
    }

    protected function saveMetaData($metaData)
    {
        $postsMeta = new Application_Model_PostsMeta();
        $postsMeta->setProjectId($this->project->getId());
        $postsMeta->setMetaKey(Tm_Constants::lASTUPDATEDON_METAKEY);
        $postsMeta->setMetaValue(date(Tm_Constants::MySqlDateTime));
        $postsMeta->save();

        if(isset($metaData->min_tag_id)) {
            $postsMeta1 = new Application_Model_PostsMeta();
            $postsMeta1->setProjectId($this->project->getId());
            $postsMeta1->setMetaKey(Tm_Constants::INSTAGRAMMINIDSTR_METAKEY);
            $postsMeta1->setMetaValue($metaData->min_tag_id);
            $postsMeta1->save();
        }

        $postsMeta2 = new Application_Model_PostsMeta();
        $postsMeta2->setProjectId($this->project->getId());
        $postsMeta2->setMetaKey(Tm_Constants::INSTAGRAMTOTALRESULTSCOUNT_METAKEY);
        $postsMeta2->setMetaValue($this->totalResultsCount);
        $postsMeta2->save();

        $postsMeta3 = new Application_Model_PostsMeta();
        $postsMeta3->setProjectId($this->project->getId());
        $postsMeta3->setMetaKey(Tm_Constants::INSTAGRAMSAVEDRESULTSCOUNT_METAKEY);
        $postsMeta3->setMetaValue($this->savedResultsCount);
        $postsMeta3->save();

        if(!empty($this->lastPostCreatedAt)) {
            $postsMeta4 = new Application_Model_PostsMeta();
            $postsMeta4->setProjectId($this->project->getId());
            $postsMeta4->setMetaKey(Tm_Constants::INSTAGRAMLASTPOSTCREATEDAT_METAKEY);
            $postsMeta4->setMetaValue($this->lastPostCreatedAt);
            $postsMeta4->save();
        }
    }

    protected function requestInstagramApi($url)
    {
        $twitterOAuth = new Tm_TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);
        $response = json_decode($twitterOAuth->http($url, 'GET'));
        return $response;
    }

    protected function getCoordinates($post)
    {
        $location = $post->location;
        if(is_object($location) && isset($location->latitude) && isset($location->longitude)) {
            return array($location->longitude, $location->latitude);
        }

        return array();
    }

    protected function getPlace($post)
    {
        if(is_object($post->location)) {
            return (isset($post->location->name)) ? $post->location->name : '';
        }
        return '';
    }

    protected function getImageUrl($post)
    {
        $images = $post->images;
        if(is_object($images)) {
            $image['standard_resolution'] = (isset($images->standard_resolution)) ? $images->standard_resolution->url : '';
            $image['thumbnail'] = (isset($images->thumbnail)) ? $images->thumbnail->url : '';
            $image['low_resolution'] = (isset($images->low_resolution)) ? $images->low_resolution->url : '';
            return $image;
        }
        return array();
    }

    protected function gettext($post)
    {
        $caption = $post->caption;
        if(is_object($caption)) {
            return (isset($caption->text)) ? $caption->text : '';
        }
        return '';
    }

    protected function saveCommunicationToLog($response, $url, $status = 200)
    {
        $dataLog = new Application_Model_DataLog();
        $dataLog->setProjectId($this->project->getId());
        $dataLog->setDescription('Fetching posts from Instagram');
        $dataLog->setTransport('GET');
        $dataLog->setOrigin('Us');
        $dataLog->setRequestValues($url);
        $dataLog->setResponseValues(print_r($response, true));
        if(200 == $status) {
            $dataLog->setStatus('Succeeded');
        } else {
            $dataLog->setStatus('Failed');
        }

        $dataLog->setStatusCode($status);
        $dataLog->setCommunicationDateTime(date(Tm_Constants::MySqlDateTime));
        $dataLog->save();
    }

}