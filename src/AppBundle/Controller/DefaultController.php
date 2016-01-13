<?php

namespace AppBundle\Controller;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller {

    protected $parameters = array(
        'author' => false,
        'from' => false,
        'to' => false,
    );

    /**
     * 
     * @Route("/", name="post_index")
     */
    public function indexAction() {
        return new Response('<html><body>Welcome !'
                . '<a href="' . $this->generateUrl('post', array()) . '">All known vdm posts</a><a href=""></a></body></html>');
    }

    /**
     * 
     * @Route("/api/posts", name="post")
     */
    public function showAllAction() {

        $expectedParameters = array_keys($this->parameters);

        $parameters = $this->getQuerystringParameters();

        if (array_key_exists($expectedParameters[0], $parameters)) {
            return $this->showByAuthorAction();
        } elseif (array_key_exists($expectedParameters[1], $parameters) || array_key_exists($expectedParameters[2], $parameters)) {
            return $this->showByDateAction();
        }

        $posts = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->findAll();


        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No posts found'
            );
        }

        return $this->renderJsonResponse(array("posts" => $posts, "count" => count($posts)));
    }

    private function renderJsonResponse($output) {

        $encoder = new JsonEncoder();
        $normalizer = new GetSetMethodNormalizer();

        $callback = function ($dateTime) {
            return $dateTime instanceof \DateTime ? $dateTime->format("Y-m-d H:i:s") : '';
        };
        $normalizer->setCallbacks(array('date' => $callback));

        $serializer = new Serializer(array($normalizer), array($encoder));

        $data = $serializer->serialize($output, 'json');
        return new Response($data);
    }

    /**
     * 
     * @Route("/api/posts/{id}", name="post_byid")
     */
    public function showByIdAction($id) {

        $posts = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->find($id);

        return $this->renderJsonResponse(array("post" => $posts));
    }

    private function showByDateAction() {

        $requestParameters = $this->getQuerystringParameters();

        $queryParameters = array();
        $query = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->createQueryBuilder('p');

        if (empty($requestParameters["from"]) === false) {
            $query->where('p.date >= :from');

            $from = \DateTime::createFromFormat("Y-m-d H:s:i", $requestParameters["from"]." 23:59:59");
            $queryParameters = array_merge(array('from' => $from));

            if (empty($requestParameters["to"]) === false) {
                $query->andWhere('p.date <= :to');
                $to = \DateTime::createFromFormat("Y-m-d H:s:i", $requestParameters["to"]." 23:59:59");
                $queryParameters = array_merge($queryParameters, array('to' => $to));
            }
        } else {
            if (empty($requestParameters["to"]) === false) {
                $query->where('p.date <= :to');
                $to = \DateTime::createFromFormat("Y-m-d H:s:i", $requestParameters["to"]." 23:59:59");
                $queryParameters = array_merge($queryParameters, array('to' => $to));
            }
        }

        $posts = $query
                ->orderBy('p.date', 'DESC')
                ->setParameters($queryParameters)
                ->getQuery()
                ->getResult();

        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No posts found'
            );
        }

        return $this->renderJsonResponse(array("posts" => $posts, "count" => count($posts)));
    }

    private function showByAuthorAction() {

        $requestParameters = $this->getQuerystringParameters();

        $queryParameters = array();
        $queryParameters = array_merge(array('author' => $requestParameters["author"]));


        $query = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->createQueryBuilder('p');


        $posts = $query
                ->where('p.author > :author')
                ->orderBy('p.date', 'DESC')
                ->setParameters($queryParameters)
                ->getQuery()
                ->getResult();

        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No posts found'
            );
        }

        return $this->renderJsonResponse(array("posts" => $posts, "count" => count($posts)));
    }

    /**
     * Valide les paramètres GET
     *
     * @return array
     * 
     * @throws HttpException
     */
    protected function getQuerystringParameters() {
        $resolver = new OptionsResolver();
        $resolver
                ->setOptional(array_keys(array_filter($this->parameters, function ($val) {
                                    return (false === $val);
                                })))
                ->setRequired(array_keys(array_filter($this->parameters, function ($val) {
                                    return (true === $val);
                                })))
        ;
        try {
            return $resolver->resolve($this->get('request')->query->all());
        } catch (\Exception $e) {
            throw new HttpException(400, 'Invalid parameters.');
        }
    }

}
