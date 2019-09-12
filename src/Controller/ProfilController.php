<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Entity\Clients;
use App\Entity\Demandes;
use App\Entity\ModeVersement;
use App\Entity\AlertePrix;
use App\Repository\DemandesRepository;

use App\Form\AdresseType;
use App\Form\InfoClientType;
use App\Form\ModeVersementType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="profil")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $session->clear();       
        
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        //$demandes = $repo->findBy(['client' => $user]);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            //'demandes' => $demandes,
        ]);
    }

    /**
     * @Route("/demandes", name="demandes")
     */
    public function demandes(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $session->clear();       
        
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $demandes = $repo->findBy(['client' => $user]);

        return $this->render('profil/demandes.html.twig', [
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }

    /**
     * @Route("/gains", name="gains")
     */
    public function remboursements(Request $request, RouterInterface $router)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $form = $this->createForm(ModeVersementType::class);
        $dossiersRembourses =  $repo->findRefundedByUser($user);
        $gains = 0;
        forEach($dossiersRembourses as $dossier){
            $gains = $gains + $dossier->getMontantRemboursement();
        }
        $flashbag = $this->get('session')->getFlashBag();
        if ($request->isMethod('POST')){
            $post = $request->request->get('mode_versement');
            $form->submit($post);
            if ($form->isValid()){
                $mode = new ModeVersement();
                
                $mode->setSwiftBic(strtoupper($post['swiftBic']));
                $mode->setIban(strtoupper($post['iban']));
                $mode->setProprietaire($post['proprietaire']);
                $user->setModeVersement($mode);
                $em->flush();
                
                $message = "Le nouveau mode de versement a bien été pris en compte";
                $flashbag->add("success", $message);
            }
            else{
                $errorIban = $form['iban']->getErrors();
                $errorBic = $form['swiftBic']->getErrors();
                $flashbag->add("warningIban", $errorIban);
                $flashbag->add("warningBic", $errorBic);
                $flashbag->add("warningIban", $errorIban);
                $flashbag->add("warningBic", $errorBic);
            }
            // Add flash message
            

            $url = $router->generate('profil');
            $url .= "#porte-monnaie";
            

            return new RedirectResponse($url);
        }

        
        return $this->render('profil/gains.html.twig', [
            'controller_name' => $gains,
            'user' => $user,
            'form' => $form->CreateView(),
        ]);
    }
    
    // /**
    // * @Route("/parrainage", name="parrainage")
    // */
    /*public function parrainages(Request $request)
    {
        $user = $this->getUser();
        return $this->render('profil/parrainage.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }*/

    /**
     * @Route("/informations-generales", name="info_client")
     */
    public function informations(Request $request, RouterInterface $router)
    {
        $emojis = ["", ";).png", "=D.png", "=o.png"];
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(InfoClientType::class, $user);
        $flashbag = $this->get('session')->getFlashBag();
        
        if ($request->isMethod('POST')){
            $post = $request->request->get('info_client');
            if($user->getNom() != $post['nom']){
                $user->setNom(ucfirst($post['nom']));
                $flashbag->add("success", "Votre changement de nom a bien été pris en compte");             
            }
            if($user->getPrenom() != $post['prenom']){
                $user->setPrenom(ucfirst($post['prenom']));
                $flashbag->add("success", "Votre changement de prénom a bien été pris en compte");             
            }
            if(array_key_exists('dateNaissance', $post) && "" !== $post['dateNaissance']['day'] ){
                $string = $post['dateNaissance']['day'] . "-" ;
                $string .=  $post['dateNaissance']['month'] . "-" . $post['dateNaissance']['year'];
                $dateNaissance = \DateTime::createFromFormat('d-m-Y',$string);
                $user->setDateNaissance($dateNaissance);
                $flashbag->add("success", "Votre changement de date de naissance a bien été pris en compte");
            }
            if($user->getNumeroTelephone() != $post['numeroTelephone']){
                if($form->isSubmitted() && $form->isValid()){
                    $user->setNumeroTelephone($post['numeroTelephone']);
                    $flashbag->add("success", "Votre changement de numéro de télephone a bien été pris en compte");
                }
                else{
                    $errors = $form->getErrors();
                    $flashbag->add("success", "Votre numéro de téléphone n'a pu être changé");
                    foreach ($errors as $error) {
                        $flashbag->add("warning", $error);
                    }
                }            
            }
            if("" != $post['adresse']['nomRue']){
                if($form->isSubmitted() && $form->isValid()){
                    $adresse = new Adresses();
                    $adresse->bis_construct($post['adresse']);
                    $user->setAdresse($adresse);
                    $flashbag->add("success", "Votre changement d'adresse a bien été pris en compte");
                }
                else{
                    $errors = $form['adresse']->getErrors();
                    foreach ($errors as $error) {
                        $flashbag->add("warning", $error);
                    }
                }          
            }

            if($user->getPhoto() != $emojis[$request->request->get('selected-text')]){
                $user->setPhoto( "/img/emoji/" . $emojis[$request->request->get('selected-text')]);
                $flashbag->add("success", "Votre changement de photo de profil a bien été pris en compte");
            }
            
            $em->flush();
            $url = $router->generate('profil');
            $url .= "#informations-personnelles";

            return new RedirectResponse($url);
        }
        return $this->render('profil/informations.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'form' => $form->CreateView(),
        ]);
    }

    /**
     * @Route("/mode_versement", name="mode_versement")
     */
    public function mode_versement(Request $request, RouterInterface $router )
    {
        if($request->isXmlHttpRequest()){
            $form = $this->createForm(ModeVersementType::class);
            $flashbag = $this->get('session')->getFlashBag();
            
            return $this->render('profil/m_versement.html.twig', ['form' => $form->CreateView()]);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/dossier/{id}", name="recap1", requirements={"id"="\d+"})
     */
    public function recapitulatif(Request $request, RouterInterface $router, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $dossier = $repo->findOneBy(["numeroDossier" => $id]);
        if(!$dossier || $dossier->getClient() != $user){
            throw $this->createNotFoundException('');
        }

        return $this->render('test.html.twig', ['dossier' => $dossier]);
    }

    /**
     * @Route("/suppression", name="suppression")
     */
    public function suppression_compte(Request $request, RouterInterface $router)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $em->remove($user);
        $em->flush($user);

        return $this->render('profil/suppression.html.twig');

    }
}
