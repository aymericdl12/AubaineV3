<?php

namespace Aubaine\PlatformBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AubaineEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    // $builder->remove('date');
  }
  public function getParent()
  {
    return AubaineType::class;
  }
}