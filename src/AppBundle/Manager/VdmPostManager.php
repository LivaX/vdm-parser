<?php

namespace AppBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\RowVdmPost;
use AppBundle\Entity\VdmPost;

class VdmPostManager {

    private $container;
    private $em;
    private $limit;

    /**
     * 
     * @param \AppBundle\Manager\Container $container
     */
    public function __construct(ContainerInterface $container, EntityManager $entityManager, $limit) {
        $this->container = $container;
        $this->limit = $limit;
        $this->em = $entityManager;
    }

    private function createPost(RowVdmPost $rowPost) {

        return new VdmPost($rowPost);
    }

    /**
     * parcourt les 200 derniers posts de viedemerde.fr
     * @return array
     */
    private function parsePosts($html) {
        $parseResult = array();
        $crawler = new Crawler($html);
        $crawler = $crawler->filterXPath("//div[@class='post article']");

        foreach ($crawler as $domElement) {

            $postCrawler = new Crawler($domElement);

            $rowId = $postCrawler->attr("id");
            $rowContent = $postCrawler->filterXPath("//p[1]")->text();

            $rowAuthorPublishDate = $postCrawler->filterXPath("(//div[@class='right_part']/p)[2]");
            $rowAuthorPublishDate = explode("-", $rowAuthorPublishDate->text());

            $rowDatetime = trim(substr($rowAuthorPublishDate[0], 2));
            $rowDate = substr($rowDatetime, 0, 10);
            $rowTime = substr($rowDatetime, -5);
            $date = \DateTime::createFromFormat('d/m/Y H:i', $rowDate . " " . $rowTime);


            $authorArray = explode(" ", trim($rowAuthorPublishDate[count($rowAuthorPublishDate) - 1]));
            $rowAuthor = $authorArray[1];

            $rowVdmPost = new RowVdmPost(array(
                "id" => $rowId,
                "content" => $rowContent,
                "author" => $rowAuthor,
                "date" => $date->format('Y-m-d H:i:s'),
            ));


            $parseResult[] = $rowVdmPost;
        }

        return $parseResult;
    }

    /**
     * 
     * Convertit des rowPosts en modelPost
     * 
     * @param mixed $rowPosts
     * @return mixed
     */
    private function convertPosts($rowPosts) {
        $posts = array();
        foreach ($rowPosts as $rowPost) {
            $posts[] = $this->createPost($rowPost);
        }
        return $posts;
    }

    /**
     * récupère les 200 derniers posts de viedemerde.fr
     * @return array
     */
    public function getLatestPosts() {

        $vdmHtml = $this->container->get('kernel')->locateResource('@AppBundle/Resources/templates/vdm.html');
        $vdmDoc = new \DOMDocument();
        @$vdmDoc->loadHTMLFile($vdmHtml);

        $rowPosts = $this->parsePosts($vdmDoc);
        $modelPosts = $this->convertPosts($rowPosts);
        $this->savePosts($modelPosts);

        return $modelPosts;
    }

    /**
     * @param array $posts
     * @return void
     */
    public function savePosts($posts) {

        foreach ($posts as $entity) {
            $this->em->persist($entity);
        }
        $this->em->flush();
    }

}
