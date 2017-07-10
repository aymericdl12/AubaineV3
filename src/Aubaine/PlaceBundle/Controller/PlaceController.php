<?php

// src/OC/PlaceBundle/Controller/AdvertController.php

namespace Aubaine\PlaceBundle\Controller;

use Aubaine\PlaceBundle\Document\Place;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\PlaceBundle\Form\Type\PlaceType;
use Aubaine\PlaceBundle\Form\Type\PlaceEditType;
use Aubaine\PlaceBundle\Form\Type\PlaceEditMainPictureType;
use Aubaine\PlaceBundle\Form\Type\PlaceEditSecondPicture1Type;
use Aubaine\PlaceBundle\Form\Type\PlaceEditSecondPicture2Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlaceController extends Controller
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

    $listPlaces = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubainePlaceBundle:Place')
      ->findAll()
      // ->getplaces($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listPlaces) / $nbPerPage);

    return $this->render('AubainePlaceBundle:Place:index.html.twig', array(
      'listPlaces' => $listPlaces,
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

    $listPlaces = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubainePlaceBundle:Place')
      ->findAll()
      // ->getplaces($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listPlaces) / $nbPerPage);

    return $this->render('AubainePlaceBundle:Place:publicIndex.html.twig', array(
      'listPlaces' => $listPlaces,
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
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);
    // $aubaine->setAuthorName('Café Cerise');
    // $dm->flush();

    return $this->render('AubainePlaceBundle:Place:view.html.twig', array(
      'place' => $place
    ));
  }

  /**
  */
  public function publicViewAction($id)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);

    return $this->render('AubainePlaceBundle:Place:publicView.html.twig', array(
      'place' => $place
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request)
  {

    $place= new Place();
    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');

    // $default_author = $repository->findOneBy(array('username' => 'Utilisateur par defaut'));

    $place->setTitle("La Place Saint Pierre");
    $place->setIntroduction("Eligendi doloremque cum libero voluptatem incidunt dicta aperiam odio quis nemo");
    $place->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi doloremque cum libero voluptatem incidunt dicta aperiam odio quis nemo totam possimus ad magni voluptatum, perferendis minus veniam sed porro eaque.");
    $place->setConclusion("Quis nemo totam possimus ad magni voluptatum, perferendis minus veniam sed porro eaque.");
    $place->setInformation("Information, perferendis minus veniam sed porro eaque.");
    // $aubaine->setAuthor($default_author);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->createForm(PlaceType::class, $place);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($place);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'l\'article bien enregistré.');

         // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('aubaine_place_home', array());
      }
    }
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('AubainePlaceBundle:Place:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);
    if (null === $place) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(PlaceEditType::class, $place);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_place_view', array("id"=>$id));
    }

    return $this->render('AubainePlaceBundle:Place:edit.html.twig', array(
      'place' => $place,
      'form'   => $form->createView()
    ));
  }


  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editMainPictureAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);
    if (null === $place) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(PlaceEditMainPictureType::class, $place);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $place->setImageHeader("blank");
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_place_view', array("id"=>$id));
    }

    return $this->render('AubainePlaceBundle:Place:edit.html.twig', array(
      'place' => $place,
      'form'   => $form->createView()
    ));
  }


  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editSecondPicture1Action($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);
    if (null === $place) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(PlaceEditSecondPicture1Type::class, $place);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $place->setImage1("blank");
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_place_view', array("id"=>$id));
    }

    return $this->render('AubainePlaceBundle:Place:edit.html.twig', array(
      'place' => $place,
      'form'   => $form->createView()
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editSecondPicture2Action($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $place = $dm->getRepository('AubainePlaceBundle:Place')->find($id);
    if (null === $place) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(PlaceEditSecondPicture2Type::class, $place);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $place->setImage1("blank");
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_place_view', array("id"=>$id));
    }

    return $this->render('AubainePlaceBundle:Place:edit.html.twig', array(
      'place' => $place,
      'form'   => $form->createView()
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlaceBundle:Place')->find($id);

    if (null === $aubaine) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "L'article a bien été supprimé.");
    return $this->redirectToRoute('aubaine_place_home');

  }


  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function menuAction($limit)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listAubaines = $dm->getRepository('AubainePlaceBundle:Aubaine')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );
    return $this->render('AubainePlaceBundle:Place:menu.html.twig', array(
      'listAubaines' => $listAubaines
    ));
  }


}
