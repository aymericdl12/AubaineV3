<?php

namespace Aubaine\PlaceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Aubaine\UserBundle\Form\Type\UserType;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',   TextType::class)
        ->add('introduction',   TextareaType::class,array('required' => false))
        ->add('content',   TextareaType::class,array('required' => false))
        ->add('information',   TextareaType::class,array('required' => false))
        ->add('category', ChoiceType::class, array(
            'choices'  => array(
                'Bar, Café, Restaurant' => "eat",
                'Boutique, épicerie' => "shop",
                'Bien être' => "wellness",
                'Évènement' => "event"
            )))
        ->add('address',     TextType::class)
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
        ->add('phone',     TextType::class, array(
            'required' => false,
        ))
        ->add('save',      SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aubaine\PlaceBundle\Document\Place',
        ));
    }
}