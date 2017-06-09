<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Aubaine\UserBundle\Controller;

use Aubaine\PlatformBundle\Document\Aubaine;
use Aubaine\UserBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\UserBundle\Form\Type\UserType;
use Aubaine\UserBundle\Form\Type\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function indexAction($page)
  {
    $userManager = $this->get('fos_user.user_manager');
    $listUsers = $userManager->findUsers();

    $nbPerPage = 3;

    return $this->render('AubaineUserBundle:User:index.html.twig', array(
      'listUsers' => $listUsers,
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function viewAction($id)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('id' => $id));

    return $this->render('AubaineUserBundle:User:view.html.twig', array(
      'user' => $user
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request)
  {

    $user= new User();

    // On crée le FormBuilder grâce au service form factory
    $form = $this->createForm(UserType::class, $user);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

        $password = $user->getPassword();
        $user->setPlainPassword($password);

        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($user);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'utilisateur bien enregistré.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('aubaine_user_view', array('id' => $user->getId()));
      }
    }

    return $this->render('AubaineUserBundle:User:add.html.twig', array(
      'form' => $form->createView(),
    ));

  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAction($id, Request $request)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('id' => $id));
    if (null === $user) {
      throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(UserEditType::class, $user);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $password = $user->getPassword();
      $user->setPlainPassword($password);
      $userManager->updateUser($user);
      $request->getSession()->getFlashBag()->add('notice', 'utilisateur mis à jour');
      return $this->redirectToRoute('aubaine_user_view', array('id' => $user->getId()));
    }

    return $this->render('AubaineUserBundle:User:edit.html.twig', array(
      'user' => $user,
      'form'   => $form->createView(),
    ));
  }
  
  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAction($id, Request $request)
  {
    
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('id' => $id));

    if (null === $user) {
      throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
    }
    $userManager->deleteUser($user);
    $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimé.");
    return $this->redirectToRoute('aubaine_user_home');

  }
}
