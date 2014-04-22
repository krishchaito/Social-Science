<?php

class Tm_Validate
{
    static public function newProjectForm(&$postData)
    {
        $errors = array();
        $status = true;

        $postData['title'] = Tm_Atrizine::GetValue($postData['title'], '');
        $postData['hashTag'] = Tm_Atrizine::GetValue($postData['hashTag'], '');
        $postData['summary'] = Tm_Atrizine::GetValue($postData['summary'], '');
        $postData['description'] = Tm_Atrizine::GetValue($postData['description'], '');
        $postData['useTwitter'] = ('on' === Tm_Atrizine::GetValue($postData['useTwitter'], 0)) ? 1 : 0;
        $postData['useInstagram'] = ('on' === Tm_Atrizine::GetValue($postData['useInstagram'], 0)) ? 1 : 0;
        $postData['usePicture'] = ('on' === Tm_Atrizine::GetValue($postData['usePicture'], 0)) ? 1 : 0;
        $postData['gpsReq'] = ('on' === Tm_Atrizine::GetValue($postData['gpsReq'], 0)) ? 1 : 0;
        $postData['tweetFormat'] = Tm_Atrizine::GetValue($postData['tweetFormat'], '');

        $trackCounter = $postData['trackCounter'];
        $trackData = array();
        $hasTrackData = false;
        if($trackCounter > 0) {
            for($count = 1; $count <= $trackCounter; $count++) {
                $key = Tm_Atrizine::GetValue($postData['trackName'.$count], '');
                $value = Tm_Atrizine::GetValue($postData['trackValue'.$count], '');
                if(!empty($key)) {
                    $hasTrackData = true;
                    $trackData[$key] = $value;
                }
            }
        }
        $postData['trackData'] = serialize($trackData);

        if(!$hasTrackData || empty($postData['title']) || empty($postData['hashTag']) || empty($postData['summary']) || empty($postData['description']) || empty($postData['tweetFormat'])) {
            $status = false;
        }

        if(!$postData['useTwitter'] && !$postData['useInstagram']) {
            $status = false;
        }
        return array($status, $errors);
    }
}