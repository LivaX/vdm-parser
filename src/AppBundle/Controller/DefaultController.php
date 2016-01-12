<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    protected $parameters = array(
        'author' => false,
        'from' => false,
        'to' => false,
    );

    /**
     * 
     * @Route("/", name="action")
     */
    public function indexAction() {
        return new Response('<html><body>Hello !</body></html>');
    }
    
    /**
     * 
     * @Route("/api/posts", name="posts")
     */
    public function showAllAction() {

        $posts = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->findAll();

        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No products found'
            );
        }

        $data = $this->get('serializer')->serialize($posts, 'json');
        return new JsonResponse($data);
    }

    /**
     * 
     * @Route("/api/posts/show", name="postsbydate")
     */
    public function showByDateAction($from = "", $to = "") {

        $parameters = $this->getQuerystringParameters();

        $queryParameters = array();
        $query = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->createQueryBuilder('p');

        if (empty($from) === false) {
            $queryParameters = array_merge(array('from' => $from));
        }

        if (empty($to) === false) {
            $queryParameters = array_merge(array('to' => $to));
        }


        $posts = $query
                ->where('p.publishAt > :from')
                ->andWhere('p.publishAt', ':to')
                ->orderBy('p.publishAt', 'DESC')
                ->setParameters($queryParameters)
                ->getQuery()
                ->getResult();

        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No products found'
            );
        }

        $data = $this->get('serializer')->serialize($posts, 'json');
        return new JsonResponse($data);
    }

    /**
     *
     * @Route("/api/posts/show", name="postbyauthor")
     */
    public function showByAuthorAction($author = "") {

        $queryParameters = array();
        if (empty($author) === false) {
            $queryParameters = array_merge(array('author' => $author));
        }

        $query = $this->getDoctrine()
                ->getRepository('AppBundle:VdmPost')
                ->createQueryBuilder('p');


        $posts = $query
                ->where('p.author > :author')
                ->orderBy('p.publishAt', 'DESC')
                ->setParameters($queryParameters)
                ->getQuery()
                ->getResult();

        if (count($posts) < 0) {
            throw $this->createNotFoundException(
                    'No products found'
            );
        }

        $data = $this->get('serializer')->serialize($posts, 'json');
        return new JsonResponse($data);
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
