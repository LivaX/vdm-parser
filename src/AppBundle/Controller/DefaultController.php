<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
                . '<a href="'.$this->generateUrl('post',array()).'">All known vdm posts</a><a href=""></a></body></html>');
    }
    
    /**
     * 
     * @Route("/api/posts", name="post")
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
        return new Response($data);
    }

    /**
     * 
     * @Route("/api/posts/show", name="post_bydate")
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
     * @Route("/api/posts/show", name="post_byauthor")
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
