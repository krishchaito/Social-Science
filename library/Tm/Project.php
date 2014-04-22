<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Date: 4/17/14
 * Time: 12:52 PM
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

    static public function getPostsByProjectIdWithPostCreatedAtDesc($projectId)
    {
        $postsMapper = new Application_Model_PostsMapper();
        return $postsMapper->fetchAllByProjectIDWithPostCreatedAtDesc($projectId);
    }

    static public function getPostsByMetaKeyDesc($metaKey, $projectId)
    {
        $postsMapper = new Application_Model_PostsMetaMapper();
        return $postsMapper->fetchByMetaKeyDesc($metaKey, $projectId);
    }
}