<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'blog.signup.form.email'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'blog.signup.form.firstName'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'blog.signup.form.lastName'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'          => PasswordType::class,
                'first_options' => [
                    'label' => 'blog.signup.form.passwordFirst'
                ],
                'second_options' => [
                    'label' => 'blog.signup.form.passwordSecond'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'blog.signup.form.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}