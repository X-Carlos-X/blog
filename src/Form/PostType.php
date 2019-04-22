<?php

namespace App\Form;

use App\Entity\Post;
use App\Type\TagInputType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título del post: ',
                'required' => 'required'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Descripción: ',
                'required' => 'required'
            ])
            ->add('createdAt',DateType::class, [
                'label' => 'Creado en: ',
                'required' => 'required'
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'Publicado en: ',
                'required' => 'required'
            ])
            ->add('modifiedAt', DateType::class, [
                'label' => 'Modificado en: ',
                'required' => 'required'
            ])
            // Author y user no lo especificado aquí ya que queremos que se muestre lueog con "post" al usuario que ha hecho el Post estnado logueado.
            ->add('tags', TagInputType::class, [
                'label' => 'tags',
                'attr' => [
                    'data-role' => 'tagsinput'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publicar'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
