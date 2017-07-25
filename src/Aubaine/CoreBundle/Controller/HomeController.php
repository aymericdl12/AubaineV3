<?php

namespace Aubaine\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use PhpImap\Mailbox;

class HomeController extends Controller
{
    // La page d'accueil
	public function indexAction(Request $request)
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getLastAubaines(array(1,2,3),3)->toArray();
	    $number_partner = $this->get('doctrine_mongodb')->getManager()->getRepository('AubaineUserBundle:User')->findAll();
	    $emailInfos = "";

	    $hoursDay="hours".date("l");

	    $number_partner = $this->get('doctrine_mongodb')->getManager()->getRepository('AubaineUserBundle:User')->findAll();

	    $serializer = $this->container->get('jms_serializer');

	    $array_response=array(
	      'hoursDay' => $hoursDay,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day,
		   'number_partner'=> sizeof($number_partner)
	    );

		return $this->render('AubaineCoreBundle:Home:index.html.twig', $array_response);
	    
	}
    // La page carte
	public function mapAction(Request $request)
	{

		$dm = $this->get('doctrine_mongodb')->getManager();

	    $current_day= strtotime(date("Y-m-d 00:00:00"));
	    $current_day_datetime = new \DateTime();
	    $current_day_datetime->setTimestamp($current_day);

	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getAubainesLive( $current_day_datetime, array('eat','shop','wellness','event'), 'toulouse' )->toArray();

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

		if($category!="all"){
		    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getAubainesLive( $current_day_datetime, array('eat','shop','wellness','event'), $city )->toArray();
		}
		else{
		    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->getAubainesLive( $current_day_datetime, array($category), $city )->toArray();
		}

	    $serializer = $this->container->get('jms_serializer');
	    $listAubainesJson = $serializer->serialize($listAubaines, "json");

	    $array_response=array(
	      'current_day_datetime' => $current_day_datetime,
	      'listAubainesJson' => $listAubainesJson,
	      'listAubaines' => $listAubaines,
	      'current_day' => $current_day
	    );

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
	// La page de cgu
	public function cguAction()
	{
		return $this->render('AubaineCoreBundle:Home:cgu.html.twig', array(

	    ));
	}
	// La page de subscription
	public function subscriptionAction()
	{
		return $this->render('AubaineCoreBundle:Home:subscription.html.twig', array(

	    ));
	}
	public function ajax_newsletterAction(Request $request)
	{

		$email=$request->request->get('email');
		$date=date('d/m/y à h\h');
		$city="toulouse";

		$list_email_file = fopen("liste_email.txt", "a+");
		$txt =$email.','.$city.','.$date."\n";
		fwrite($list_email_file, $txt);
		fclose($list_email_file);

		$response = new Response();
		$response->setContent(json_encode( array( 'response' => "Vous avez bien été enregistrée" ) ));
		$response->headers->set('Content-Type', 'application/json');
		return $response;

	}
    // La page carte
	public function emailAction(Request $request)
	{
		$response="coco";
		$emails=array();

		// $mbox = imap_open("{imap.gmail.com:993/imap/ssl}INBOX", "aymeric.lanouvelle@gmail.com", "****");
		// $MC = imap_check($mbox);
		// $emails = imap_fetch_overview($mbox,"1:{$MC->Nmsgs}",0);
		// imap_close($mbox);



		// $emails = imap_list($mbox, "{imap.gmail.com:993/imap/ssl}INBOX", "*");

		// $response = imap_qprint(imap_body($mbox, 1));

	    $array_response=array(
	      'emails' => $emails,
	      'response'=>$response
	    );

		return $this->render('AubaineCoreBundle:Home:email.html.twig', $array_response);
	    
	}
}
