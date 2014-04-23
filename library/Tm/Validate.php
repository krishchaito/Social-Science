<?php

class Tm_Validate
{
    static public function newProjectForm(&$postData)
    {
        $errors = array();
        $hasError = false;

        $postData['title'] = trim(Tm_Atrizine::GetValue($postData['title'], ''));
        $postData['hashTag'] = trim(Tm_Atrizine::GetValue($postData['hashTag'], ''));
        $postData['summary'] = trim(Tm_Atrizine::GetValue($postData['summary'], ''));
        $postData['description'] = trim(Tm_Atrizine::GetValue($postData['description'], ''));
        $postData['useTwitter'] = ('on' === Tm_Atrizine::GetValue($postData['useTwitter'], 0)) ? 1 : 0;
        $postData['useInstagram'] = ('on' === Tm_Atrizine::GetValue($postData['useInstagram'], 0)) ? 1 : 0;
        $postData['usePicture'] = ('on' === Tm_Atrizine::GetValue($postData['usePicture'], 0)) ? 1 : 0;
        $postData['gpsReq'] = ('on' === Tm_Atrizine::GetValue($postData['gpsReq'], 0)) ? 1 : 0;
        $postData['tweetFormat'] = trim(Tm_Atrizine::GetValue($postData['tweetFormat'], ''));

        $trackCounter = $postData['trackCounter'];
        $trackData = array();
        $hasTrackData = false;
        if($trackCounter > 0) {
            for($count = 1; $count <= $trackCounter; $count++) {
                $key = trim(Tm_Atrizine::GetValue($postData['trackName'.$count], ''));
                $value = trim(Tm_Atrizine::GetValue($postData['trackValue'.$count], ''));
                if(!empty($key)) {
                    $hasTrackData = true;
                    $trackData[$key] = $value;
                }
            }
        }
        $postData['trackData'] = serialize($trackData);

        if(!$hasTrackData || empty($postData['title']) || empty($postData['hashTag']) || empty($postData['summary']) || empty($postData['description']) || empty($postData['tweetFormat'])) {
            $hasError = true;
        }

        if(!$postData['useTwitter'] && !$postData['useInstagram']) {
            $hasError = true;
        }
        return array($hasError, $errors);
    }
}