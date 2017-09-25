<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(array(), ['indexOrder' => 'ASC']);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/{slug}", requirements={"slug": "^(?!login).+"}, name="post")
     */
    public function showAction($slug)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);

        return $this->render('default/page.html.twig', [
            'post' => $post
        ]);
    }
}
