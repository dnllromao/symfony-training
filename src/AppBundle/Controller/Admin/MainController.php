<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Utils\Slugger;
use AppBundle\Utils\FileUploader;

/**
* Controller used to manage blog contents in the backend. 
*
* @Route("/admin")
*/
class MainController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(array(), ['indexOrder' => 'ASC']);

        return $this->render('admin/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/new", name="admin_post_new")
     */
    public function newAction(Request $request, Slugger $slugger, FileUploader $fileUploader)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post)
            ->add('save', SubmitType::class, array( 'label' => 'Créer'));
        
        $form->handleRequest($request);

        // max file upload of 2M set on php.ini
        if($form->isSubmitted() && $form->isValid()) {
            
            // img procedure
            $file = $form['imgFile']->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $post->setImg($fileName);
            }
            // img procedure

            // // generates slug
            $slug = $slugger->slugify($post->getTitle());
            $post->setSlug($slug);
            // // end generates slug

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_homepage');

        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->CreateView()
        ]);
    }


    /**
     * @Route("/edit/{slug}", name="admin_post_edit")
     */
    public function editAction(Request $request, Slugger $slugger, $slug, FileUploader $fileUploader)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);


        $form = $this->createForm(PostType::class, $post)
                    ->add('save', SubmitType::class, array( 'label' => 'Sauvegarder'));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            // img procedure
            $file = $form['imgFile']->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $post->setImg($fileName);
            }
            // img procedure

            // generates slug
            $slug = $slugger->slugify($post->getTitle());
            $post->setSlug($slug);
            // end generates slug

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_homepage');

        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->CreateView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/delete/{slug}", name="admin_post_delete")
     */
    public function deleteAction(Request $request, $slug)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('admin_homepage');
    }


}