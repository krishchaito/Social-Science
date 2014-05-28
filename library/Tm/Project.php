<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 */

/**
 * Class Tm_Project
 */
class Tm_Project
{
    /**
     * @param $id
     * @return Application_Model_Projects
     */
    static public function getById($projectId)
    {
        $projectsMapper = new Application_Model_ProjectsMapper();
        return $projectsMapper->fetchByID($projectId);
    }

    static public function getAllProjects()
    {
        $projectsMapper = new Application_Model_ProjectsMapper();
        return $projectsMapper->fetchAll();
    }

    static public function getPostsByProjectIdWithPostCreatedAtDesc($projectId)
    {
        $postsMapper = new Application_Model_PostsMapper();
        return $postsMapper->fetchAllByProjectIDWithPostCreatedAtDesc($projectId);
    }

    static public function getPostsByProjectIdWithSearchDateString($projectId, $startDate, $endDate)
    {
        $postsMapper = new Application_Model_PostsMapper();
        return $postsMapper->fetchAllByProjectIDBetweenDates($projectId, $startDate, $endDate);
    }

    static public function getPostsByMetaKeyDesc($metaKey, $projectId)
    {
        $postsMapper = new Application_Model_PostsMetaMapper();
        return $postsMapper->fetchByMetaKeyDesc($metaKey, $projectId);
    }

    static public function getDomainUrl() {
        $protocol = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME']: 'http';
        return $protocol.'://'.$_SERVER['HTTP_HOST'];
    }
}