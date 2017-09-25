<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Utils\Slugger;

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
    public function newAction(Request $request, Slugger $slugger)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post)
            ->add('save', SubmitType::class, array( 'label' => 'Créer'));
        
        $form->handleRequest($request);
        // max file upload of 2M set on php.ini
        //if($form->isSubmitted() && $form->isValid()) {
        if($form->isSubmitted()) {

            $slug = $slugger->slugify($post->getTitle());
            $post->setSlug($slug);

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
    public function editAction(Request $request, $slug)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);

        $form = $this->createFormBuilder($post)
                    ->add('title', TextType::class )
                    ->add('intro', TextareaType::class)
                    ->add('content', CKEditorType::class)
                    ->add('img', FileType::class, array( 'required' => false))
                    ->add('index_order', IntegerType::class)
                    ->add('save', SubmitType::class, array( 'label' => 'send message'))
                    ->getForm();

        $form->handleRequest($request);

        //if($form->isSubmitted() && $form->isValid()) {
        if($form->isSubmitted()) {

            $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
            $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");

            $slug = str_replace(' ', '-', mb_strtolower(str_replace($search, $replace, $post->getTitle()), 'UTF-8') ) ;
            $post->setSlug($slug);

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