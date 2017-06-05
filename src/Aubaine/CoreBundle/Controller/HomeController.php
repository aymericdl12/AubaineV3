<?php

namespace Aubaine\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    // La page d'accueil
	public function indexAction()
	{

		$dm = $this->get('doctrine_mongodb')->getManager();
	    $listAubaines = $dm->getRepository('AubainePlatformBundle:Aubaine')->findAll();
	    $listPartners = $dm->getRepository('AubaineUserBundle:User')->findAll();

	    $serializer = $this->container->get('jms_serializer');
	    $listAubainesJson = $serializer->serialize($listAubaines, "json");
	    $listPartnersJson = $serializer->serialize($listPartners, "json");

		return $this->render('AubaineCoreBundle:Home:index.html.twig', array(
	      'listPartnersJson' => $listPartnersJson,
	      'listAubainesJson' => $listAubainesJson,
	      'listAubaines' => $listAubaines
	    ));
	    
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
		return $this->render('AubaineCoreBundle:Home:concept.html.twig', array(

	    ));
	}
	// La page de concept
	public function partnerAction()
	{
		return $this->render('AubaineCoreBundle:Home:partner.html.twig', array(

	    ));
	}
}
