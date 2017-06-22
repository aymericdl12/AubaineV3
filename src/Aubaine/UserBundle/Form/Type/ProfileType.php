<?php

namespace Aubaine\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('description')
        ->add('addressDisplayed')
        ->add('zipcode')
        ->add('city', ChoiceType::class, array(
            'choices'  => array(
                'Toulouse' => "toulouse",
                'Bordeaux' => "bordeaux",
                'Lyon' => "lyon",
                'Paris' => "paris",
                'Marseille' => "marseille",
                'New York' => "newyork"
            )))
        ->add('hoursMonday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursTuesday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursWednesday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursThursday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursFriday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursSaturday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->add('hoursSunday',     TextType::class, array('required' => false,'attr' => array(
                'placeholder' => 'Exemple: 8:00-12:00, 14:00-19:00'
            )))
        ->remove('current_password')
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}