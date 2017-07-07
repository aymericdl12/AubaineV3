<?php

namespace Aubaine\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class HomeController extends Controller
{
    // La page d'accueil
	public function indexAction()
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

	    $lastAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getLastAubaines(3)->toArray();
	    $lastPlaces = $dm->getRepository('AubainePlaceBundle:Place')->getLastPlaces(3)->toArray();
	    $lastEvents = $dm->getRepository('AubaineEventBundle:Event')->getLastEvents(3)->toArray();
	    $number_partner = $this->get('doctrine_mongodb')->getManager()->getRepository('AubaineUserBundle:User')->findAll();

	    $hoursDay="hours".date("l");

	    $serializer = $this->container->get('jms_serializer');
	    $listPlacesJson = $serializer->serialize($lastPlaces, "json");

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'number_partner' => sizeof($number_partner),
	      'listPlacesJson' => $listPlacesJson,
	      'lastAubaines' => $lastAubaines,
	      'lastEvents' => $lastEvents,
	      'lastPlaces' => $lastPlaces,
	      'current_day' => $current_day
	    );

	    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
	    	$userId= $this->get('security.token_storage')->getToken()->getUser()->getId();
	        $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->getCurrentAubaineByAuthor($userId, $current_day_datetime);
	        $array_response['current_aubaine'] = $current_aubaine;
	    }

		return $this->render('AubaineCoreBundle:Home:index.html.twig', $array_response);
	    
	}
    // La page carte
	public function mapAction()
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

	    $listPlaces = $dm->getRepository('AubainePlaceBundle:Place')->findBy(
		    array('published' => True),
		    array('city' => 'toulouse')
		);
	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
		    array('city' => 'toulouse')
		);

	    $hoursDay="hours".date("l");

	    $serializer = $this->container->get('jms_serializer');
	    $listPlacesJson = $serializer->serialize($listPlaces, "json");
	    $listAubainesJson = $serializer->serialize($listAubaines, "json");

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listPlacesJson' => $listPlacesJson,
	      'listPlaces' => $listPlaces,
	      'listAubainesJson' => $listAubainesJson,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day
	    );

	    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
	    	$userId= $this->get('security.token_storage')->getToken()->getUser()->getId();
	        $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->getCurrentAubaineByAuthor($userId, $current_day_datetime);
	        $array_response['current_aubaine'] = $current_aubaine;
	    }

		return $this->render('AubaineCoreBundle:Home:map.html.twig', $array_response);
	    
	}
    // La page carte
	public function liveAction()
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
		    // array('published' => True),
		    // array('hasMessage' => True),
		    array('city' => 'toulouse')
		);

	    $hoursDay="hours".date("l");

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day
	    );

	    // if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
	    // 	$userId= $this->get('security.token_storage')->getToken()->getUser()->getId();
	    //     $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->getCurrentAubaineByAuthor($userId, $current_day_datetime);
	    //     $array_response['current_aubaine'] = $current_aubaine;
	    // }

		return $this->render('AubaineCoreBundle:Home:live.html.twig', $array_response);
	    
	}
	// La page de contact
	public function contactAction(Request $request)
	{
		$session = $request->getSession();
		$session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible, merci de revenir plus tard.');
		return $this->redirectToRoute('oc_core_home');
	}
	// La page de concept
	public function conceptAction()
	{

		$number_partner = $this->get('doctrine_mongodb')->getManager()->getRepository('AubaineUserBundle:User')->findAll();
		return $this->render('AubaineCoreBundle:Home:concept.html.twig', array(
			'number_partner'=> sizeof($number_partner)
	    ));
	}
	// La page de concept
	public function partnerAction()
	{
		return $this->render('AubaineCoreBundle:Home:partner.html.twig', array(

	    ));
	}
	// La page de concept
	public function cguAction()
	{
		return $this->render('AubaineCoreBundle:Home:cgu.html.twig', array(

	    ));
	}
	public function ajax_newsletterAction(Request $request)
	{

		$email=$request->request->get('email');
		$date=date('d/m/y à h\h');

		$list_email_file = fopen("liste_email.txt", "a+");
		$txt =$email.','.$date."\n";
		fwrite($list_email_file, $txt);
		fclose($list_email_file);

		$response = new Response();
		$response->setContent(json_encode( array( 'message' => "Email bien enregistrée" ) ));
		$response->headers->set('Content-Type', 'application/json');
		return $response;

	}
}
