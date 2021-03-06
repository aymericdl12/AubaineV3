<?php

namespace Aubaine\PlatformBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
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

class AubaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('message',   TextareaType::class)
        ->add('category', ChoiceType::class, array(
            'choices'  => array(
                'Bar, Café, Restaurant' => "eat",
                'Boutique, épicerie' => "shop",
                'Bien être' => "wellness",
                'Évènement' => "event"
            )))
        ->add('place', DocumentType::class, array(
            'class'        => 'AubainePlaceBundle:Place',
            'choice_label' => 'title'
          ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Toute l\'année' => 1,
                'Plusieurs jours' => 2,
                'Uniquement un jour' => 3
            )))
        ->add('start',   DateType::class)
        ->add('end',   DateType::class)
        ->add('save',      SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aubaine\PlatformBundle\Document\Aubaine',
        ));
    }
}