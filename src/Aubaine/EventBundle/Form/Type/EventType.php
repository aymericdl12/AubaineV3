<?php

namespace Aubaine\EventBundle\Form\Type;

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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',   TextType::class)
        ->add( 'imageFileHeader', FileType::class )
        ->add('introduction',   TextareaType::class)
        ->add( 'imageFile1', FileType::class )
        ->add('content',   TextareaType::class)
        ->add( 'imageFile2', FileType::class )
        ->add('conclusion',   TextareaType::class)
        ->add('information',   TextareaType::class)
        ->add('lati',     TextType::class)  
        ->add('longi',     TextType::class)
        ->add('save',      SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aubaine\EventBundle\Document\Event',
        ));
    }
}