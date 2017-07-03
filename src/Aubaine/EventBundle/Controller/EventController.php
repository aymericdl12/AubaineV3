<?php

// src/OC/EventBundle/Controller/AdvertController.php

namespace Aubaine\EventBundle\Controller;

use Aubaine\EventBundle\Document\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\EventBundle\Form\Type\EventType;
use Aubaine\EventBundle\Form\Type\EventEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EventController extends Controller
{

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function indexAction($page)
  {
    if($page==""){
        $page=1;
    }
    if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    $nbPerPage = 100;

    $current_day= strtotime(date("Y-m-d 00:00:00"));
    $current_day_datetime = new \DateTime();
    $current_day_datetime->setTimestamp($current_day);

    $listEvents = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubaineEventBundle:Event')
      ->findAll()
      // ->getevents($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listEvents) / $nbPerPage);

    return $this->render('AubaineEventBundle:Event:index.html.twig', array(
      'listEvents' => $listEvents,
      'nbPages'     => $nbPages,
      'page'        => $page,
    ));
  }


  /**
  */
  public function publicIndexAction($page)
  {
    if($page==""){
        $page=1;
    }
    if ($page < 1) {
        throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    $nbPerPage = 100;

    $current_day= strtotime(date("Y-m-d 00:00:00"));
    $current_day_datetime = new \DateTime();
    $current_day_datetime->setTimestamp($current_day);

    $listEvents = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubaineEventBundle:Event')
      ->findAll()
      // ->getevents($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listEvents) / $nbPerPage);

    return $this->render('AubaineEventBundle:Event:publicIndex.html.twig', array(
      'listEvents' => $listEvents,
      'nbPages'     => $nbPages,
      'page'        => $page,
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function viewAction($id)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $event = $dm->getRepository('AubaineEventBundle:Event')->find($id);
    // $aubaine->setAuthorName('Café Cerise');
    // $dm->flush();

    return $this->render('AubaineEventBundle:Event:view.html.twig', array(
      'event' => $event
    ));
  }

  /**
  */
  public function publicViewAction($id)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $event = $dm->getRepository('AubaineEventBundle:Event')->find($id);

    return $this->render('AubaineEventBundle:Event:publicView.html.twig', array(
      'event' => $event
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request)
  {

    $event= new Event();
    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');

    // $default_author = $repository->findOneBy(array('username' => 'Utilisateur par defaut'));

    $event->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, dolor.");
    // $aubaine->setAuthor($default_author);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->createForm(EventType::class, $event);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'l\'article bien enregistré.');

         // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('aubaine_event_home', array());
      }
    }
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('AubaineEventBundle:Event:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $event = $dm->getRepository('AubaineEventBundle:Event')->find($id);
    if (null === $event) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(EventEditType::class, $event);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_event_home');
    }

    return $this->render('AubaineEventBundle:Event:edit.html.twig', array(
      'event' => $event,
      'form'   => $form->createView()
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubaineEventBundle:Event')->find($id);

    if (null === $aubaine) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "L'article a bien été supprimé.");
    return $this->redirectToRoute('aubaine_event_home');

  }


  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function menuAction($limit)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listAubaines = $dm->getRepository('AubaineEventBundle:Aubaine')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );
    return $this->render('AubaineEventBundle:Event:menu.html.twig', array(
      'listAubaines' => $listAubaines
    ));
  }


}
