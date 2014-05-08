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

// Fetch data from twitter/Instagram
foreach($projects as $key => $project) {
    if($project->getUseTwitter()) {
        $twitter = new Tm_Twitter();
        $twitter->update($project);
    }

    if($project->getUseInstagram()) {
        $twitter = new Tm_Instagram();
        $twitter->update($project);
    }

    $response = array('projectId' => $project->getId(),'status' => 'success', 'statusCode' => 200);
    print_r($response);
}