<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Year: 2014
 */

// Set environment
require_once 'environment.php';

// Fetch all projects
$projects = Tm_Project::getAllProjects();

// Open file for logging
$filePath = APPLICATION_PATH . '/../../logs/update_projects_log';
$file = fopen($filePath, 'a');
if(!$file) {
    die('Could not open log file');
}

// Fetch data from twitter/Instagram
foreach($projects as $key => $project) {
    if($project->getUseTwitter()) {
        $twitter = new Tm_Twitter();
        list($hasErrors, $twitterResult) = $twitter->update($project);

        foreach($twitterResult as $result) {
            $twitterStatus = array(date(Tm_Constants::MySqlDateTime),
                Tm_Constants::TWITTER,
                $project->getId(),
                $project->getTitle(),
                $result['code'],
                $result['message']
            );

            fwrite($file, implode(' | ', $twitterStatus)."\n");
        }
    }

    if($project->getUseInstagram()) {
        $instagram = new Tm_Instagram();
        list($hasErrors, $instagramResult) = $instagram->update($project);

        foreach($instagramResult as $result) {
            $instagramStatus = array(date(Tm_Constants::MySqlDateTime),
                Tm_Constants::INSTAGRAM,
                $project->getId(),
                $project->getTitle(),
                $result['code'],
                $result['message']
            );

            fwrite($file, implode(' | ', $instagramStatus)."\n");
        }
    }
}

fclose($file);