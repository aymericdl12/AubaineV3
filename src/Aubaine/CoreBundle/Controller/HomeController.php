<?php

namespace Aubaine\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

	    $listAubaines1 = $dm->getRepository('AubainePlatformBundle:Aubaine')->getLastAubaines(1,3)->toArray();
	    $listAubaines2 = $dm->getRepository('AubainePlatformBundle:Aubaine')->getLastAubaines(2,3)->toArray();
	    $listAubaines3 = $dm->getRepository('AubainePlatformBundle:Aubaine')->getLastAubaines(3,3)->toArray();
	    $number_partner = $this->get('doctrine_mongodb')->getManager()->getRepository('AubaineUserBundle:User')->findAll();

	    $hoursDay="hours".date("l");

	    $serializer = $this->container->get('jms_serializer');

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listAubaines1' => $listAubaines1,
	      'listAubaines2' => $listAubaines2,
	      'listAubaines3' => $listAubaines3,
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
	public function mapAction(Request $request)
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);
	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
		    array('city' => 'toulouse')
		);

	    $hoursDay="hours".date("l");

	    $serializer = $this->container->get('jms_serializer');
	    $listAubainesJson = $serializer->serialize($listAubaines, "json");

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listAubainesJson' => $listAubainesJson,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day
	    );

		return $this->render('AubaineCoreBundle:Home:map.html.twig', $array_response);
	    
	}
    // La page carte
	public function liveAction($city,$category,$search, Request $request)
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

		if($search=="all"){
			if($category!="all"){
			    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
				    array('city' => $city, 'category' => $category)
				);
			}
			else{
			    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findBy(
				    array('city' => $city)
				);
			}	    	
		}

	    $hoursDay="hours".date("l");

	    $serializer = $this->container->get('jms_serializer');
	    $listAubainesJson = $serializer->serialize($listAubaines, "json");

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listAubainesJson' => $listAubainesJson,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day
	    );

	    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
	    	$userId= $this->get('security.token_storage')->getToken()->getUser()->getId();
	        $current_aubaine = $dm->getRepository('AubainePlatformBundle:Aubaine')->getCurrentAubaineByAuthor($userId, $current_day_datetime);
	        $array_response['current_aubaine'] = $current_aubaine;
	    }

		return $this->render('AubaineCoreBundle:Home:live.html.twig', $array_response);
	    
	}

	public function searchAction(Request $request)
	{

		$city=$request->request->get('city');
		$category=$request->request->get('category');

		return new RedirectResponse($this->generateUrl('aubaine_core_live', array( 'city' => $city,'category' => $category, 'search' => 'all')));
	    
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
