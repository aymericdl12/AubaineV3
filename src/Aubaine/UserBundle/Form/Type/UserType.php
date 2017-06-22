<?php

namespace Aubaine\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username',     TextType::class)
        ->add('email',     TextType::class)
        ->add('description',     TextareaType::class)
        ->add('addressDisplayed',     TextType::class)
        ->add('zipcode',     TextType::class)
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
                'placeholder' => 'ex: 8:00-12:00, 14:00-19:00',
                'autocomplete' => 'off',
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
        ->add('lati',     TextType::class)  
        ->add('longi',     TextType::class)
        ->add('category', ChoiceType::class, array(
            'choices'  => array(
                'Bar, Café, Restaurant' => "eat",
                'Boutique, épicerie' => "shop",
                'Bien être' => "wellness",
                'Évènement' => "event"
            )))
        ->add('imageFile',     FileType::class)
        ->add('password',     TextType::class)
        ->add('enabled',     CheckboxType::class)
        ->add('save',      SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aubaine\UserBundle\Document\User',
        ));
    }
}