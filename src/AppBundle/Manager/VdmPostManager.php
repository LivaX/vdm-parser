<?php

namespace AppBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;

class VdmPostManager {

    private $container;
    private $limit;

    /**
     * 
     * @param \AppBundle\Manager\Container $container
     */
    public function __construct(ContainerInterface $container, $limit) {
        $this->container = $container;
        $this->limit = $limit;
    }

    /**
     * parcourt les 200 derniers posts de viedemerde.fr
     * @return array
     */
    private function parsePosts($html) {
        $parseResult = array();
        $crawler = new Crawler($html);
        $crawler = $crawler->filterXPath('descendant-or-self::body/p');
        
        foreach ($crawler as $domElement) {
            var_dump(array($domElement->nodeName => $domElement->textContent));
            $parseResult[] = $domElement->nodeName;
        }

        return $parseResult;
    }

    /**
     * récupère les 200 derniers posts de viedemerde.fr
     * @return array
     */
    public function getLatestPosts() {

        $vdmHtml = $this->container->get('kernel')->locateResource('@AppBundle/Resources/vdm.html');
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p class="message">Hello World!</p>
        <p>Hello Crawler!</p>
    </body>
</html>
HTML;





        $vdmDoc = new \DOMDocument();
        @$vdmDoc->loadHTMLFile($vdmHtml);



        return $this->parsePosts($vdmDoc);
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
