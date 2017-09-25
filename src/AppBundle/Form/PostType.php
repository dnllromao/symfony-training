<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Post;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
//use Symfony\Component\Form\Extension\Core\Type\ImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class )
            ->add('intro', TextareaType::class, array( 'required' => false))
            ->add('content', CKEditorType::class)
            ->add('imgFile', FileType::class, [
        		'required' => false,
                'mapped' => false,
                'constraints' => new Assert\Image()
            ])
            ->add('index_order', IntegerType::class, array( 'required' => false))
        ;

    }

    // did noy understand this (http://symfony.com/doc/current/form/data_transformers.html)
    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'data_class' => Post::class,
    //     ));
    // }
}