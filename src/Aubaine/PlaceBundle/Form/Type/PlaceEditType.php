<?php

namespace Aubaine\PlaceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlaceEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // $builder->remove('imageFileHeader')
            // ->remove('imageFile1')
            // ->remove('imageFile2');
  }
  public function getParent()
  {
    return PlaceType::class;
  }
}