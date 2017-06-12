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
        ->add('hoursMonday',     TextType::class, array('required' => false))
        ->add('hoursTuesday',     TextType::class, array('required' => false))
        ->add('hoursWednesday',     TextType::class, array('required' => false))
        ->add('hoursThursday',     TextType::class, array('required' => false))
        ->add('hoursFriday',     TextType::class, array('required' => false))
        ->add('hoursSaturday',     TextType::class, array('required' => false))
        ->add('hoursSunday',     TextType::class, array('required' => false) )
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
        ->add('city',     TextType::class)
        ->add('password',     TextType::class)
        ->add('enabled',     RadioType::class)
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