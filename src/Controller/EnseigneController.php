<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeInterface;

use App\Form\AdresseType;
use App\Form\DemandesMagasinType;
use App\Form\DemandesInternetType;

use App\Entity\Adresses;
use App\Entity\Clients;
use App\Entity\Enseignes;
use App\Entity\Demandes;
use App\Entity\Magasins;

use App\Services\Mailer;

/**
 * @Route("/demande-remboursement")
 */
class EnseigneController extends AbstractController
{
    /**
     * @Route("/", name="enseigne")
     */
    public function index(Request $request, Mailer $mailer)
    {
        //On récupère les informations de la page d'accueil
        $post = $request->request->get('eligibilite');
        
        //On stocke les informations de la page d'accueil
        $session = $request->getSession();
        if($post != null){    
            
            $session->set('enseigne', $post['enseigne']);
            $session->set('date_achat', $post['date_achat']);
            $session->set('categorie', $post['categorie']);
            $session->set('prix', $post['prix']);
        }
        
        //On vérifie que le client est connecté
        if(!$this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('connexion');
        }
        
        $user = $this->getUser();
        
        //On vérifie que le client a entré une enseigne valide
        $nom_enseigne = ucfirst($session->get('enseigne'));
        $repoEnseigne = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repoEnseigne->findOneBy(['nomEnseigne' => $nom_enseigne]);
        
        $demande = new Demandes();
        $demande->setClient($user);

        $repoDemandes = $this->getDoctrine()->getRepository(Demandes::class);
        $demandes = $repoDemandes->findAll();
        $count = count($demandes);
        $demande->setNumeroDossier($count);
        $session->set('numDossier', $demande->getNumeroDossier());

        if($enseigne == null){
            return $this->render('enseigne/404_enseigne.html.twig');
        }

        $formMagasin = $this->createForm(DemandesMagasinType::class, $demande,[
           'validation_groups' => array('User', 'inscription'),
        ]);
        $formMagasin->handleRequest($request); 
         
        $formInternet = $this->createForm(DemandesInternetType::class, $demande,[
            'validation_groups' => array('User', 'inscription'),
         ]);
        $formInternet->handleRequest($request); 
        
        
        $this->handle_form($user, $demande, $formMagasin, $session, $mailer);
        $this->handle_form($user, $demande, $formInternet, $session, $mailer);
        
        return $this->render('enseigne/index.html.twig', [
            'enseigne' => $enseigne,
            //'form1' => $formMagasin->CreateView(), 
            //'form2' => $formInternet->CreateView(), 
            'user' => $user,
        ]);
    }
    
    /**
     * @Route("/magasin", name="mag")
     */
    public function formMagasin(Request $request){
        $response = new Response();
        $user = $this->getUser();
        $demande = new Demandes();
        $demande->setClient($user);
        $formMagasin = $this->createForm(DemandesMagasinType::class, $demande,[
            'validation_groups' => array('User', 'inscription'),
        ]);
        $formMagasin->handleRequest($request);

        return $this->render('enseigne/magasin.html.twig', ['form1' => $formMagasin->CreateView(), 'user' => $user]);
    }


    /**
     * @Route("/internet", name="internet")
     */
    public function formInternet(Request $request){
        $response = new Response();
        $user = $this->getUser();
        $demande = new Demandes();
        $demande->setClient($user);
        $formInternet = $this->createForm(DemandesInternetType::class, $demande);
        $formInternet->handleRequest($request); 

        return $this->render('enseigne/internet.html.twig', ['form1' => $formInternet->CreateView(), 'user' => $user]);
    }

    public function handle_form($user, $demande, $form, $session, Mailer $mailer){
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $demande->setPrixAchat($session->get('prix'));
            $dateAchat = \DateTime::createFromFormat('m-d-Y', $session->get('date_achat'));
            $demande->setCategorieProduit($session->get('categorie'));
            $demande->setEnseigne($session->get('enseigne'));
            $demande->setDateAchat($dateAchat);

            $em->persist($user);
            $em->persist($demande);
            $em->flush();

            $bodyMail = $mailer->createBodyMail('enseigne/mail2.html.twig', [ 'user' => $user,
                'demande' => $demande
            ]);
            $mailer->sendMessage('from@email.com', $user->getEmail(), 'Confirmation du dépot de dossier', $bodyMail);

            //$data = ['path' => $demande];
            //return new JsonResponse($data);
            return $this->redirectToRoute('profil');
        }
    }
}
