<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Aubaine\PlatformBundle\Controller;

use Aubaine\PlatformBundle\Document\Aubaine;
use Aubaine\PlatformBundle\Document\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\PlatformBundle\Form\Type\AubaineType;
use Aubaine\PlatformBundle\Form\Type\AubaineEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AubaineController extends Controller
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

    $listAubaines = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubainePlatformBundle:Aubaine')
      ->getAubaines($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listAubaines) / $nbPerPage);

    return $this->render('AubainePlatformBundle:Aubaine:index.html.twig', array(
      'listAubaines' => $listAubaines,
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
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);
    // $aubaine->setAuthorName('Café Cerise');
    // $dm->flush();

    return $this->render('AubainePlatformBundle:Aubaine:view.html.twig', array(
      'aubaine' => $aubaine
    ));
  }

  public function publicViewAction($id)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);
    // $aubaine->setAuthorName('Café Cerise');
    // $dm->flush();

    return $this->render('AubainePlatformBundle:Aubaine:publicView.html.twig', array(
      'aubaine' => $aubaine
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request)
  {

    $aubaine= new Aubaine();
    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');

    // $default_author = $repository->findOneBy(array('username' => 'Utilisateur par defaut'));

    // $aubaine->setMessage("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, dolor.");
    // $aubaine->setAuthor($default_author);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->createForm(AubaineType::class, $aubaine);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

        $aubaine->setPlaceId($aubaine->getPlace()->getId());
        $aubaine->setCity($aubaine->getPlace()->getCity());
        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($aubaine);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien enregistrée.');

         // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('aubaine_platform_home', array());
      }
    }
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('AubainePlatformBundle:Aubaine:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);
    if (null === $aubaine) {
      throw new NotFoundHttpException("L'aubaine d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(AubaineEditType::class, $aubaine);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_platform_home');
    }

    return $this->render('AubainePlatformBundle:Aubaine:edit.html.twig', array(
      'aubaine' => $aubaine,
      'form'   => $form->createView()
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);

    if (null === $aubaine) {
      throw new NotFoundHttpException("L'aubaine d'id ".$id." n'existe pas.");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "Le message a bien été supprimé.");
    return $this->redirectToRoute('aubaine_platform_home');

  }

  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function deleteAubaineAction($id, Request $request)
  {
    $id_place= $this->get('security.token_storage')->getToken()->getUser()->getPlaceId();

    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);

    if (null === $aubaine) {
      throw new NotFoundHttpException("Le message d'id ".$id_aubaine." n'existe pas.");
    }
    if($aubaine->getPlaceId()!=$id_place){
      throw new NotFoundHttpException("Vous n'avez pas la permission de supprimer ce message");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "Le message a bien été supprimé.");

    // $redirect_to = $request->request->get('redirect_to');
    // return $this->redirectToRoute($redirect_to);
    return $this->redirectToRoute('aubaine_platform_my_aubaines');
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function deleteAllAction(Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findAll();

    $userManager = $this->get('fos_user.user_manager');
    $users=$userManager->findUsers();

    foreach ($aubaines as $aubaine) {
     $value->setCity("toulouse");
    }

    foreach ($users as $user) {
     $user->setCity("toulouse");
    }

    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "Toutes les aubaines ont été supprimée.");
    return $this->redirectToRoute('aubaine_platform_home');

  }

  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function menuAction($limit)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );
    return $this->render('AubainePlatformBundle:Aubaine:menu.html.twig', array(
      'listAubaines' => $listAubaines
    ));
  }

  public function load_markerAction()
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findAll();

    $serializer = $this->container->get('jms_serializer');
    $listAubainesJson = $serializer->serialize($listAubaines, "json");

    $response = new Response();
    $response->setContent($listAubainesJson);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }


  /**
   * @Security("has_role('ROLE_USER')")
   */
  public function my_aubainesAction(Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $user = $this->get('security.token_storage')->getToken()->getUser();
    $placesId = $user->getPlacesId();
    $places=[];
    $currentAubaines = [];
    $oldAubaines = [];
    foreach ($placesId as $placeId) {
      $places[$placeId] = $dm->getRepository('AubainePlaceBundle:Place')->find($placeId);
      $currentAubaines[$placeId] = $dm->getRepository('AubainePlatformBundle:Aubaine')->getCurrentAubaines($placeId, new \DateTime('now') );
      $oldAubaines[$placeId] = $dm->getRepository('AubainePlatformBundle:Aubaine')->getOldAubaines($placeId, new \DateTime('now') );
      $permanentAubaines[$placeId] = $dm->getRepository('AubainePlatformBundle:Aubaine')->getPermanentAubaines($placeId);
    }

    if ($request->isMethod('POST')) {
      $aubaine= new Aubaine();
      $aubaine->setTitle($request->request->get('title'));
      $aubaine->setMessage($request->request->get('message'));
      $aubaine->setStart($request->request->get('start'));
      $aubaine->setEnd($request->request->get('end'));
      $aubaine->setPlace( $places[ $request->request->get('place') ] );
      $aubaine->setPlaceId( $places[ $request->request->get('place') ]->getId() );
      $aubaine->setCity( $places[ $request->request->get('place') ]->getCity());
      $aubaine->setCategory( $request->request->get('category') );
      if($request->request->get('permanent')){
        $aubaine->setPermanent( True );
      }  
      $dm->persist($aubaine);
      $dm->flush();
      $request->getSession()->getFlashBag()->add('info', "Le message a bien été publié.");
    }

    return $this->render('AubainePlatformBundle:Aubaine:myAubaines.html.twig', array(
        'places' => $places,
        'currentAubaines' => $currentAubaines,
        'oldAubaines' => $oldAubaines,
        'permanentAubaines' => $permanentAubaines
      ));
  }

}
