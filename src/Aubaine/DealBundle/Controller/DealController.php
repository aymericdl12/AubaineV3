<?php

// src/OC/DealBundle/Controller/AdvertController.php

namespace Aubaine\DealBundle\Controller;

use Aubaine\DealBundle\Document\Deal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Aubaine\DealBundle\Form\Type\DealType;
use Aubaine\DealBundle\Form\Type\DealEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DealController extends Controller
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

    $listDeals = $this->get('doctrine_mongodb')
      ->getManager()
      ->getRepository('AubaineDealBundle:Deal')
      ->findAll()
      // ->getdeals($current_day_datetime, $page, $nbPerPage)
      ;

    $nbPages = ceil(count($listDeals) / $nbPerPage);

    return $this->render('AubaineDealBundle:Deal:index.html.twig', array(
      'listDeals' => $listDeals,
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
    $deal = $dm->getRepository('AubaineDealBundle:Deal')->find($id);
    // $aubaine->setAuthorName('Café Cerise');
    // $dm->flush();

    return $this->render('AubaineDealBundle:Deal:view.html.twig', array(
      'deal' => $deal
    ));
  }

  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request)
  {

    $deal= new Deal();
    $repository = $this->get('doctrine_mongodb')
    ->getManager()
    ->getRepository('AubaineUserBundle:User');

    // $default_author = $repository->findOneBy(array('username' => 'Utilisateur par defaut'));

    $deal->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, dolor.");
    // $aubaine->setAuthor($default_author);

    // On crée le FormBuilder grâce au service form factory
    $form = $this->createForm(DealType::class, $deal);

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {

        $em = $this->get('doctrine_mongodb')->getManager();
        $em->persist($deal);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'l\'article bien enregistré.');

         // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('aubaine_deal_home', array());
      }
    }
    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('AubaineDealBundle:Deal:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $deal = $dm->getRepository('AubaineDealBundle:Deal')->find($id);
    if (null === $deal) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $form = $this->get('form.factory')->create(DealEditType::class, $deal);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $deal->setTitle("contenu edite nouvelle image");
      $dm->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Aubaine bien modifiée.');
      return $this->redirectToRoute('aubaine_deal_home');
    }

    return $this->render('AubaineDealBundle:Deal:edit.html.twig', array(
      'deal' => $deal,
      'form'   => $form->createView()
    ));
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function deleteAction($id, Request $request)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $aubaine = $dm->getRepository('AubaineDealBundle:Deal')->find($id);

    if (null === $aubaine) {
      throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
    }
    $dm->remove($aubaine);
    $dm->flush();
    $request->getSession()->getFlashBag()->add('info', "L'article a bien été supprimé.");
    return $this->redirectToRoute('aubaine_deal_home');

  }


  /**
  * @Security("has_role('ROLE_USER')")
  */
  public function menuAction($limit)
  {
    $dm = $this->get('doctrine_mongodb')->getManager();
    $listAubaines = $dm->getRepository('AubaineDealBundle:Aubaine')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );
    return $this->render('AubaineDealBundle:Deal:menu.html.twig', array(
      'listAubaines' => $listAubaines
    ));
  }


}
