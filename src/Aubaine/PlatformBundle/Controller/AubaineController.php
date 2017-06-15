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

    $listAubaines = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubainePlatformBundle:Aubaine')
      ->getAubaines($page, $nbPerPage)
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

    $aubaine->setMessage("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, dolor.");
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

        $aubaine->setIdAuthor($aubaine->getAuthor()->getId());
        $aubaine->setCategory($aubaine->getAuthor()->getCategory());
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
  public function addMultipleAction(Request $request)
  {

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
        $repository = $this->get('doctrine_mongodb')
        ->getManager()
        ->getRepository('AubaineUserBundle:User');
        $userId= $request->request->get('author_id');
        $author=$repository->findOneBy(array('id' =>$userId));
        $message=$request->request->get('message');
        $first_date = strtotime( $request->request->get('start') );
        $last_date = strtotime( $request->request->get('end') );
        $days_validity = $request->request->get('days_validity');
        $em = $this->get('doctrine_mongodb')->getManager();
        $cpt=0;
        $removed=0;
        $date = $first_date;
        $dm = $this->get('doctrine_mongodb')->getManager();
        while ( $date <= $last_date) {

            if(in_array( date("l",$date), $days_validity) ){
                $date_datetime = new \DateTime();
                $date_datetime->setTimestamp($date);
                $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->findOneBy(array('idAuthor' =>$userId, 'date'=>$date_datetime));
                if($current_aubaine){
                    $current_aubaine->setMessage($message);
                    $removed++;
                }
                else{
                    $aubaine= new Aubaine();
                    $aubaine->setMessage($message);
                    $aubaine->setDate($date_datetime);
                    $aubaine->setAuthor($author);
                    $aubaine->setCategory($author->getCategory());
                    $aubaine->setIdAuthor($userId);
                    $em->persist($aubaine);
                }
                $cpt++;
            }
            $date+=24*3600;
        }
        $em->flush();
        return $this->redirectToRoute('aubaine_platform_home', array());
    }
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listusers = $dm->getRepository('AubaineUserBundle:User')->findAll();
    return $this->render('AubainePlatformBundle:Aubaine:addMultiple.html.twig', array(
      'listusers' => $listusers
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
    $request->getSession()->getFlashBag()->add('info', "L'aubaine a bien été supprimée.");
    return $this->redirectToRoute('aubaine_platform_home');

  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAubaineAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id);
    if (null === $aubaine) {
        throw new NotFoundHttpException("L'aubaine d'id ".$id." n'existe pas.");
    }
    $month = date('F',$aubaine->getDate()->getTimestamp());
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "L'aubaine a bien été supprimée.");
    return $this->redirectToRoute('aubaine_platform_my_aubaines', array('month' => $month));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function deleteAllAction(Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->findAll();

    foreach ($aubaine as $value) {
     $dm->remove($value);
    }

    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "Toutes les aubaines ont été supprimée.");
    return $this->redirectToRoute('aubaine_platform_home');

  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function populateAction()
  {
    $aubaine = new Category();
    $aubaine2 = new Category();
    $aubaine3 = new Category();
    $aubaine4 = new Category();

    $aubaine->setSlug('boire_manger');

    $aubaine2->setSlug('courses');

    $aubaine3->setSlug('bien_etre');

    $aubaine4->setSlug('divertir');

    $dm = $this->get('doctrine_mongodb')->getManager();
    

    $dm->persist($aubaine);
    $dm->persist($aubaine2);
    $dm->persist($aubaine3);
    $dm->persist($aubaine4);

    $dm->flush();

    return new Response('Categories créées');
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
  public function ajax_add_aubaineAction(Request $request)
  {
    $aubaine= new Aubaine();
    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');

    $author= $this->get('security.token_storage')->getToken()->getUser();
    $userId=$author->getId();

    $message=$request->request->get('message');
    $aubaine->setMessage($message);

    $aubaine->setDate($request->request->get('date'));
    $aubaine->setAuthor($author);
    $aubaine->setIdAuthor($userId);
    $aubaine->setCategory($author->getCategory());
    
    $em = $this->get('doctrine_mongodb')->getManager();
    $em->persist($aubaine);
    $em->flush();

    $delete_link = $this->generateUrl( 'aubaine_platform_ajax_delete_aubaine' );

    $response = new Response();
    $response->setContent(json_encode( array( 'message' => $message , 'delete_link' => $delete_link, 'id_aubaine'=>$aubaine->getId() ) ));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function ajax_add_aubaine_multipleAction(Request $request)
  {

    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');
    $author= $this->get('security.token_storage')->getToken()->getUser();
    $userId=$author->getId();
    $message=$request->request->get('message');
    $first_date = strtotime( $request->request->get('first_date') );
    $last_date = strtotime( $request->request->get('last_date') );
    $days_validity = $request->request->get('days_validity');
    $em = $this->get('doctrine_mongodb')->getManager();
    $cpt=0;
    $removed=0;
    $date = $first_date;
    $dm = $this->get('doctrine_mongodb')->getManager();
    while ( $date <= $last_date) {

        if(in_array( date("l",$date), $days_validity) ){
            $date_datetime = new \DateTime();
            $date_datetime->setTimestamp($date);
            $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->findOneBy(array('idAuthor' =>$userId, 'date'=>$date_datetime));
            if($current_aubaine){
                $current_aubaine->setMessage($message);
                $removed++;
            }
            else{
                $aubaine= new Aubaine();
                $aubaine->setMessage($message);
                $aubaine->setDate($date_datetime);
                $aubaine->setAuthor($author);
                $aubaine->setIdAuthor($userId);
                $aubaine->setCategory($author->getCategory());
                $em->persist($aubaine);
            }
            $cpt++;
        }
        $date+=24*3600;
    }
    $em->flush();
    $response = new Response();
    $response->setContent(json_encode( array( 'message' => $removed ) ));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function ajax_delete_aubaineAction(Request $request)
  {
    $id_aubaine = $request->request->get('id_aubaine');
    $id_user= $this->get('security.token_storage')->getToken()->getUser()->getId();

    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->find($id_aubaine);

    if (null === $aubaine) {
      throw new NotFoundHttpException("L'aubaine d'id ".$id_aubaine." n'existe pas.");
    }
    if($aubaine->getIdAuthor()!=$id_user){
      throw new NotFoundHttpException("Vous n'avez pas la permission de supprimer ce message");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "Le message a bien été supprimée.");

    $response = new Response();
    $response->setContent(json_encode( array( 'message' => "succes" ) ));
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * @Security("has_role('ROLE_USER')")
   */
  public function my_aubainesAction($week=0, Request $request)
  {
    if(!$week){
        $week=date('o'."\W".'W');
    }
    $current_day= strtotime(date("Y-m-d 00:00:00"));
    $week_first_day = strtotime($week);
    $week_last_day = $week_first_day + 6*(+ 24*3600);
    $user= $this->get('security.token_storage')->getToken()->getUser();
    $userId=$user->getId();
    $days=array();
    $day_timestamp = $week_first_day;
    while ($day_timestamp <= $week_last_day) {
        $aubaine=new Aubaine();
        $days[$day_timestamp]=$aubaine;
        $day_timestamp = $day_timestamp + 24*3600;
    } 

    // list
    $dm = $this->get('doctrine_mongodb')->getManager();
    $week_first_day_datetime = new \DateTime();
    $week_last_day_datetime = new \DateTime();
    $week_first_day_datetime->setTimestamp($week_first_day);
    $week_last_day_datetime->setTimestamp($week_last_day);
    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getAubainesByAuthor($week_first_day_datetime,$userId, $week_last_day_datetime);
    foreach ($listAubaines as $key => $aubaine) {
      $day_timestamp = $aubaine->getDate()->getTimestamp();
      if(array_key_exists($day_timestamp, $days)){
        $days[$day_timestamp]= $aubaine;
      }
    }

    // get current aubaine for the map if not in the right week
    $current_day_datetime = new \DateTime();
    $current_day_datetime->setTimestamp($current_day);
    $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->findOneBy(array('idAuthor' => $userId, 'date'=>$current_day_datetime));
    if(!$current_aubaine){
        $current_aubaine = new Aubaine();
    }

    $serializer = $this->container->get('jms_serializer');
    $userJson = $serializer->serialize($user, "json");
    $current_aubaineJson = $serializer->serialize($current_aubaine, "json");
    $previousWeek = date('o'."\W".'W', strtotime('-1 week',$week_first_day));
    $nextWeek = date('o'."\W".'W', strtotime('+1 week',$week_first_day));


    return $this->render('AubainePlatformBundle:Aubaine:myAubaines.html.twig', array(
        'current_day' => $current_day,
        'current_aubaine' => $current_aubaineJson,
        'week' => $week,
        'previousWeek' => $previousWeek,
        'nextWeek' => $nextWeek,
        'listAubaines' => $listAubaines,
        'days' => $days,
        'user' => $userJson
      ));
  }

}
