<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Aubaine\AdminBundle\Controller;

use Aubaine\PlatformBundle\Document\Aubaine;
use Aubaine\UserBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\UserBundle\Form\Type\UserType;
use Aubaine\UserBundle\Form\Type\UserEditType;

class AdminController extends Controller
{
  public function indexAction()
  {
    return $this->render('AubaineAdminBundle:Dashboard:index.html.twig', array());
  }
}
