<?php

namespace Aubaine\DealBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DealEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // $builder->remove('date');
  }
  public function getParent()
  {
    return DealType::class;
  }
}