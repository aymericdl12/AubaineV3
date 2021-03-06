<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace Aubaine\UserBundle\Controller;

use Aubaine\PlatformBundle\Document\Aubaine;
use Aubaine\PlaceBundle\Document\Place;
use Aubaine\UserBundle\Document\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\UserBundle\Form\Type\UserType;
use Aubaine\UserBundle\Form\Type\UserEditType;
use Aubaine\UserBundle\Form\Type\UserEditAvatarType;
use Aubaine\UserBundle\Form\Type\UserEditPasswordType;
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
  public function viewAction($id, Request $request)
  {
    $userManager = $this->get('fos_user.user_manager');
    $dm = $this->get('doctrine_mongodb')->getManager();

    $user = $userManager->findUserBy(array('id' => $id));
    $placesId = $user->getPlacesId();
    $places=[];
    if(count($placesId)>0){
      foreach ($placesId as $placeId) {
        $place=  $dm->getRepository('AubainePlaceBundle:Place')->find($placeId);
        if($place){
          $places[]=  $place;
        }
      }      
    }

    $listPlaces = $dm->getRepository('AubainePlaceBundle:Place')->findAll();

    if ($request->isMethod('POST')) {
      $placeId = $request->request->get('placeId');
      $place=  $dm->getRepository('AubainePlaceBundle:Place')->find($placeId);
      if($place){
        $places[]=  $place;
        $user->addPlaceId($placeId);
        $userManager->updateUser($user);
        $request->getSession()->getFlashBag()->add('info', 'Le lieu a bien été ajouté à l\'utilisateur');
      }
      else{
        $request->getSession()->getFlashBag()->add('info', 'Le lieu n\'a pas été trouvé');
      }
    }


    return $this->render('AubaineUserBundle:User:view.html.twig', array(
      'user' => $user,
      'places'=>$places,
      'listPlaces'=>$listPlaces
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
      if ($form->isValid()) {

        $password = $user->getPassword();
        $user->setPlainPassword($password);
        $user->setProfessional(True);

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
  public function editPasswordAction($id, Request $request)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('id' => $id));
    if (null === $user) {
      throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(UserEditPasswordType::class, $user);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $password = $user->getPassword();
      $user->setPlainPassword($password);
      $userManager->updateUser($user);
      $request->getSession()->getFlashBag()->add('notice', 'utilisateur mis à jour');
      return $this->redirectToRoute('aubaine_user_view', array('id' => $user->getId()));
    }

    return $this->render('AubaineUserBundle:User:editPassword.html.twig', array(
      'user' => $user,
      'form'   => $form->createView(),
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAvatarAction($id, Request $request)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('id' => $id));
    if (null === $user) {
      throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(UserEditAvatarType::class, $user);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $user->setImageName("blank");
      $userManager->updateUser($user);
      $request->getSession()->getFlashBag()->add('notice', 'utilisateur mis à jour');
      return $this->redirectToRoute('aubaine_user_home');
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
  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAllAction(Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $userManager = $this->get('fos_user.user_manager');

    // $aubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findAll();
    // foreach ($aubaines as $aubaine) {
        // $date = new \DateTime();
        // $date->setTimestamp(1500163200);
        // $aubaine->setDate($date);
      // $dm->remove($aubaine);
    //   $author = $userManager->findUserBy(array( 'id' => $aubaine->getIdAuthor() ));
    //   $placeId = $author->getPlacesId()[0];
    //   $place = $dm->getRepository('AubainePlaceBundle:Place')->find($placeId);
    //   $aubaine->setPlace($place);
    //   $aubaine->setPlaceId($place->getId());
    //   $aubaine->setPermanent( True );
    // }


    // $places = $dm->getRepository('AubainePlaceBundle:Place')->findAll();
    // foreach ($places as $place) {
      // $place->setScore(0);
      // $place->setImage1("");
      // $place->setImage2("");
      // $aubaine = new Aubaine();
      // $aubaine->setPlace($place);
      // $aubaine->setPlaceId($place->getId());
      // $aubaine->setPermanent( True );
      // $aubaine->setDate( new \DateTime('-3 days') );
      // $aubaine->setStart( new \DateTime("now") );
      // $aubaine->setEnd( new \DateTime("+3 year") );
      // $aubaine->setCity( $place->getCity() );
      // $aubaine->setMessage( "Message" );
      // $aubaine->setType( 1 );
      // $aubaine->setCategory( $place->getCategory() );
      // $dm->persist($aubaine);
    // }


    // $users=$userManager->findUsers();
    $userEmail = "aymericdl@hotmail.fr";
    $user = $userManager->findUserByEmail($userEmail);
    // foreach ($users as $user) {
        // $user->setProfessional(True); //set to professional

        // $user->removePreference("5958fb9415605c2dbc006f2b");
        // $user->addPreference("5958fb9415605c2dbc006f30");
        // $user->addPreference("5958fb9415605c2dbc006f2b");

        // $place= new Place();
        // $dm = $this->get('doctrine_mongodb')->getManager();
        // $repository = $this->get('doctrine_mongodb')
        // ->getManager()
        // ->getRepository('AubainePlaceBundle:Place');

        // $user->setPlacesId( array( $user->getPlaceId() ) );
        // $place->setIntroduction($user->getDescription());
        // $place->setAddress($user->getAddressDisplayed());
        // $place->setCity($user->getCity());
        // $place->setCategory($user->getCategory());
        // $place->setZipcode($user->getZipcode());
        // $place->setLati($user->getLati());
        // $place->setLongi($user->getLongi());
        // $place->setHoursMonday($user->getHoursMonday());
        // $place->setHoursTuesday($user->getHoursTuesday());
        // $place->setHoursWednesday($user->getHoursWednesday());
        // $place->setHoursThursday($user->getHoursThursday());
        // $place->setHoursFriday($user->getHoursFriday());
        // $place->setHoursSaturday($user->getHoursSaturday());
        // $place->setHoursSunday($user->getHoursSunday());
        // $place->setContent("");
        // $place->setConclusion("");
        // $place->setInformation("");
        // $place->setThumbnail($user->getImageName());
        // $place->setImageHeader("59578b2ad8147.png");
        // $place->setImage1("59578290c4b9e.png");
        // $place->setImage2("59578290c40fd.png");
        // $place->setPublished(False);

        // $dm->persist($place);


        // $user->setPlaceId($place->getId());
        // $userManager->updateUser($user,false);
    // }

    $dm->flush();
    if (null === $user) {
      $request->getSession()->getFlashBag()->add('info', "Utilisateur non trouvé");
    }
    else{
      $request->getSession()->getFlashBag()->add('info', "Utilisateur trouvé et détruit");
    }
    return $this->redirectToRoute('aubaine_user_home');

  }
  
  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function importAction(Request $request)
  {
    set_time_limit(0); 
    $file_handle = fopen('users.csv', 'r');
    $cpt=0;
    while (!feof($file_handle) ) {
        $line = fgetcsv($file_handle, 80024,";");
        $line_of_text[] = $line;
        if($cpt>0){
          $user= new User();
          $user->setUsername($line[2]);
          $user->setEmail($line[1]);
          $user->setAddressDisplayed($line[5]);
          $user->setLati($line[6]);
          $user->setLongi($line[7]);
          $user->setPhone($line[8]);
          $user->setDescription($line[3]);
          $user->setHoursMonday($line[10]);
          $user->setHoursTuesday($line[11]);
          $user->setHoursWednesday($line[12]);
          $user->setHoursThursday($line[13]);
          $user->setHoursFriday($line[14]);
          $user->setHoursSaturday($line[15]);
          $user->setHoursSunday($line[16]);
          $user->setZipcode(31000);
          $user->setCity("Toulouse");
          $user->setCategory($line[4]);
          $user->setPlainPassword("aubaine");
          if($line[4]){
              if(strpos($line[4],"bars_cafes") !== false){
                $user->setCategory("eat");
              }
              if(strpos($line[4],"boutiques") !== false){
                $user->setCategory("shop");
              }
              if(strpos($line[4],"Petites faims") !== false){
                $user->setCategory("eat");
              }
              if(strpos($line[4],"Petites faims") !== false){
                $user->setCategory("eat");
              }
              if(strpos($line[4],"restauration") !== false){
                $user->setCategory("eat");
              }
              if(strpos($line[4],"petite_faim") !== false){
                $user->setCategory("eat");
              }
              if(strpos($line[4],"epiceries") !== false){
                $user->setCategory("shop");
              }
              if(strpos($line[4],"Se faire Dorlotter") !== false){
                $user->setCategory("wellness");
              }
              if(strpos($line[4],"Se faire dorlotter") !== false){
                $user->setCategory("wellness");
              }
              if(strpos($line[4],"Bien-Être") !== false){
                $user->setCategory("wellness");
              }
              if(strpos($line[4],"bien_etre") !== false){
                $user->setCategory("wellness");
              }
          }
          $em = $this->get('doctrine_mongodb')->getManager();
          $em->persist($user);
          $em->flush();
        }
        $cpt++;
        // $line_of_text[] = explode(fgetcsv($file_handle, 80024)[0],";");
    }
    fclose($file_handle);
    return $this->render('AubaineUserBundle:User:import.html.twig', array(
      'listUsers' => $line_of_text
    ));

  }
  
  
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function myProfileAction(Request $request)
  {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $user= $this->get('security.token_storage')->getToken()->getUser();
        $places=[];

        if($user->getProfessional()){
          $placesId = $user->getPlacesId();
          foreach ($placesId as $placeId) {
            $places[$placeId] = $dm->getRepository('AubainePlaceBundle:Place')->find($placeId);
          }          
        }
    

        return $this->render('AubaineUserBundle:User:myProfile.html.twig', array(
          'user' => $user,
          'places' => $places
        ));

    }
  
  
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function ajaxTogglePreferenceAction(Request $request)
  {
      $userManager = $this->get('fos_user.user_manager');
      $action=$request->request->get('addorremove');
      $preference=$request->request->get('preference');
      $user = $this->get('security.token_storage')->getToken()->getUser();
      
      $dm = $this->get('doctrine_mongodb')->getManager();
      $place = $dm->getRepository('AubainePlaceBundle:Place')->find($preference);

      if($action=="add"){
        $score= $place->getScore()+1;
        $place->setScore($score);
        $user->addPreference($preference);
        $userManager->updateUser($user);
      }
      else{
        $score= $place->getScore()-1;
        if($score<0){
          $score=0;
        }
        $place->setScore($score);
        $user->removePreference($preference);
        $userManager->updateUser($user);
      }
      $dm->flush();

      $response = new Response();
      $response->setContent(json_encode( array( 'response' => "preference: ".$preference." enregistrée", "score"=>$score ) ));
      $response->headers->set('Content-Type', 'application/json');
      return $response;

  }
  
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function ajaxAddPreferenceAction(Request $request)
  {
      $userManager = $this->get('fos_user.user_manager');
      $preference=$request->request->get('preference');
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $user->addPreference($preference);
      $userManager->updateUser($user);

      $dm = $this->get('doctrine_mongodb')->getManager();
      $place = $dm->getRepository('AubainePlaceBundle:Place')->find($preference);
      $score= $place->getScore()+1;
      $place->setScore($score);
      $dm->flush();

      $response = new Response();
      $response->setContent(json_encode( array( 'response' => "preference: ".$preference." ajoutée" ) ));
      $response->headers->set('Content-Type', 'application/json');
      return $response;

  }   
  
  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function ajaxRemovePreferenceAction(Request $request)
  {
      $userManager = $this->get('fos_user.user_manager');
      $preference=$request->request->get('preference');
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $user->removePreference($preference);
      $userManager->updateUser($user);

      $dm = $this->get('doctrine_mongodb')->getManager();
      $place = $dm->getRepository('AubainePlaceBundle:Place')->find($preference);
      $score= $place->getScore()-1;
      if($score<0){
        $score=0;
      }
      $place->setScore($score);
      $dm->flush();

      $response = new Response();
      $response->setContent(json_encode( array( 'response' => "preference: ".$preference." retirée" ) ));
      $response->headers->set('Content-Type', 'application/json');
      return $response;

  } 

} 