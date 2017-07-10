<?php

namespace AppBundle\Form;

use AppBundle\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datetime', DateTimeType::class, [
                'label' => 'dashboard.post.form.datetime'
            ])
            ->add('title', TextType::class, [
                'label' => 'dashboard.post.form.title'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'dashboard.post.form.content'
            ])
            ->add('category', EntityType::class, [
                'class'         => 'AppBundle\Entity\Category',
                'choice_label'  => 'name',
                'label'         => 'dashboard.post.form.category'
            ])
            ->add('tags', EntityType::class, [
                'class'         => 'AppBundle\Entity\Tag',
                'choice_label'  => 'name',
                'label'         => 'dashboard.post.form.tags',
                'expanded'      => false,
                'multiple'      => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}