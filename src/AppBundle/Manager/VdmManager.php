<?php

namespace AppBundle\AppBundle\Manager;

class VdmPostManager {

    /**
     * Constructor
     *
     * @param EntityManager $em
     * @param string $entityName
     * 
     * @return void
     */
    public function __construct() {


        //creating the VdmManager
    }

    /**
     * parcourt les 200 derniers posts de viedemerde.fr
     * @return array
     */
    private function parsePosts() {
        
    }

    /**
     * récupère les 200 derniers posts de viedemerde.fr
     * @return array
     */
    public function getLatestPosts() {
        return array("voici les post");
    }

    /**
     * @param array $posts
     * @return void
     */
    public function savePosts($posts) {
        
    }

    /**
     * @param VdmPost $post
     *
     * @return void
     */
    public function savePost($post) {
        
    }
    
    
    /**
     * Retourne les posts au format json
     * 
     * @param array $posts
     * @return void
     */
    private function convertPostToJson($posts) {
        return json_encode($posts);
        
    }

}
