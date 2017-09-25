<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class )
            ->add('intro', TextareaType::class, array( 'required' => false))
            ->add('content', CKEditorType::class)
            ->add('img', FileType::class, [
            		'required' => false
            ])
            ->add('index_order', IntegerType::class, array( 'required' => false))
            ->add('save', SubmitType::class, array( 'label' => 'Créer'))
        ;
    }
}