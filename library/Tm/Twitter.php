<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 */

/**
 * Class Twitter
 */
class Tm_Twitter
{

    protected $consumerKey;
    protected $consumerSecret;
    protected $accessToken;
    protected $accessTokenSecret;

    protected $tweetMaxCount = 100;
    protected $searchTweetsUrl = 'search/tweets';
    protected $totalResultsCount = 0;
    protected $savedResultsCount = 0;
    protected $sinceId = 0;
    protected $lastTweetCreatedAt;
    protected $maxSimultaneousRequests = 3;
    protected $query = array();

    /**
     * @var Object Application_Model_Projects
     */
    protected $project = '';

    /**
     * Construct Tm_Twitter object
     */
    function __construct()
    {

    }

    public function update(Application_Model_Projects &$project)
    {
        $this->project = $project;
        $this->loadConfig();

        for($requestNo = 0; $requestNo < $this->maxSimultaneousRequests; $requestNo++) {
            if((empty($this->totalResultsCount) && $requestNo < 1) || ($this->totalResultsCount == 100)) {
                $this->updateTweets();
            }
        }
    }

    protected function updateTweets()
    {
        $postsMetaMapper = new Application_Model_PostsMetaMapper();
        $maxIdStr = $postsMetaMapper->fetchByMetaKeyDesc(Tm_Constants::TWITTERMAXIDSTR_METAKEY, $this->project->getId());

        if(is_object($maxIdStr)) {
            $this->sinceId = $maxIdStr->getMetaValue();
        }

        $this->lastTweetCreatedAt = 0;
        $this->savedResultsCount = 0;

        $results = $this->requestTwitterApi($this->project->getHashTag());

        if(isset($results->errors)) {
            $this->saveErrorCommunicationToLog($results);
        } else {
            // Save communication to dataLog
            $this->saveSuccessCommunicationToLog($results);

            // Check and Save all results in DB.
            foreach($results->statuses as $tweet) {
                if(!isset($tweet->retweeted_status)) {
                    // Check if we already have this tweet
                    $post = new Application_Model_PostsMapper();
                    $result = $post->fetchByPostIdStrAndProjectId($tweet->id_str, $this->project->getId());

                    if(!is_object($result)) {
                        // Save tweet
                        $this->saveTweet($tweet);
                        $this->savedResultsCount++;

                        if(empty($this->lastTweetCreatedAt)) {
                            $this->lastTweetCreatedAt = date(Tm_Constants::MySqlDateTime, strtotime($tweet->created_at));
                        }
                    }
                }
            }
            $this->totalResultsCount = count($results->statuses);
            $this->saveMetaData($results->search_metadata);
            $this->saveProject();
        }
    }

    protected function saveProject()
    {
        $numOfTweets = $this->project->getNumOfTweets() + $this->savedResultsCount;
        $this->project->setNumOfTweets($numOfTweets);
        $this->project->save();
    }

    protected function saveTweet($tweet)
    {
        $postDb = new Application_Model_Posts();
        $postDb->setProjectId($this->project->getId());
        $postDb->setPostIdStr($tweet->id_str);
        $postDb->setText($tweet->text);
        $postDb->setUserIdStr($tweet->user->id_str);
        $postDb->setUsername($tweet->user->name);
        $postDb->setUserScreenName($tweet->user->screen_name);
        $postDb->setSource(Tm_Constants::TWITTER);
        $postDb->setPostCreatedAt(date(Tm_Constants::MySqlDateTime, strtotime($tweet->created_at)));

        $coordinates = $this->getCoordinates($tweet);
        if(!empty($coordinates)) {
            $postDb->setCoordinatesLongitude($coordinates[0]);
            $postDb->setCoordinatesLatitude($coordinates[1]);
        }

        list($place, $countryCode) = $this->getPlaceAndCountryCode($tweet);
        $postDb->setPlace($place);
        $postDb->setCountryCode($countryCode);
        $postDb->setImageURL($this->getImageUrl($tweet));
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

        $postsMeta1 = new Application_Model_PostsMeta();
        $postsMeta1->setProjectId($this->project->getId());
        $postsMeta1->setMetaKey(Tm_Constants::TWITTERMAXIDSTR_METAKEY);
        $postsMeta1->setMetaValue($metaData->max_id_str);
        $postsMeta1->save();

        $postsMeta2 = new Application_Model_PostsMeta();
        $postsMeta2->setProjectId($this->project->getId());
        $postsMeta2->setMetaKey(Tm_Constants::TWITTERTOTALRESULTSCOUNT_METAKEY);
        $postsMeta2->setMetaValue($this->totalResultsCount);
        $postsMeta2->save();

        $postsMeta3 = new Application_Model_PostsMeta();
        $postsMeta3->setProjectId($this->project->getId());
        $postsMeta3->setMetaKey(Tm_Constants::TWITTERSAVEDRESULTSCOUNT_METAKEY);
        $postsMeta3->setMetaValue($this->savedResultsCount);
        $postsMeta3->save();

        if(!empty($this->lastTweetCreatedAt)) {
            $postsMeta4 = new Application_Model_PostsMeta();
            $postsMeta4->setProjectId($this->project->getId());
            $postsMeta4->setMetaKey(Tm_Constants::TWITTERLASTTWEETCREATEDAT_METAKEY);
            $postsMeta4->setMetaValue($this->lastTweetCreatedAt);
            $postsMeta4->save();
        }
    }

    protected function requestTwitterApi($hashTag)
    {
        $twitterOAuth = new Tm_TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $query = array(
            "q" => $hashTag,
            "count" => $this->tweetMaxCount
        );

        if(!empty($this->sinceId)) {
            $query['since_id'] = $this->sinceId;
        }

        $this->query = $query;
        $results = $twitterOAuth->get($this->searchTweetsUrl, $query);
        return $results;
    }

    protected function getPlaceAndCountryCode($tweet)
    {
        $place = $tweet->place;
        if(is_object($place)) {
            return array($place->full_name, $place->country_code);
        }
        return array('', '');
    }

    protected function getCoordinates($tweet)
    {
        $coordinates = $tweet->coordinates;
        if(is_object($coordinates)) {
            return array($coordinates->coordinates[0], $coordinates->coordinates[1]);
        }
        return array();
    }

    protected function getImageUrl($tweet)
    {
        $entities = $tweet->entities;
        if(!is_object($entities)) {
            return '';
        }

        if(isset($entities->media) && is_array($entities->media)) {
            $media = $entities->media[0];
            if(is_object($media)) {
                $url = (isset($media->media_url_https)) ? $media->media_url_https : '';
                return $url;
            }
        }
        return '';
    }

    protected function saveErrorCommunicationToLog($response)
    {
        $error = $response->errors;
        $this->saveCommunicationToLog($response, $error[0]->code, $error[0]->message);
    }

    protected function saveSuccessCommunicationToLog($response)
    {
        $this->saveCommunicationToLog($response);
    }

    protected function saveCommunicationToLog($response, $statusCode = 200, $message = '')
    {
        $dataLog = new Application_Model_DataLog();
        $dataLog->setProjectId($this->project->getId());
        $dataLog->setDescription('Fetching tweets from Twitter');
        $dataLog->setTransport('OAuth');
        $dataLog->setOrigin('Us');
        $dataLog->setRequestValues(print_r($this->query, true));
        $dataLog->setResponseValues(print_r($response, true));
        if(200 == $statusCode) {
            $dataLog->setStatus('Succeeded');
        } else {
            $dataLog->setStatus('Failed');
            $dataLog->setField1($message);
        }

        $dataLog->setStatusCode($statusCode);
        $dataLog->setCommunicationDateTime(date(Tm_Constants::MySqlDateTime));
        $dataLog->save();
    }

    protected function loadConfig()
    {
        $config = new Zend_Config_Json(APPLICATION_PATH . '/configs/twitter.json', APPLICATION_ENV);
        $this->consumerKey = $config->get('consumerKey');
        $this->consumerSecret = $config->get('consumerSecret');
    }

}