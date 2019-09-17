<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeInterface;

use App\Security\FormLoginAuthenticator;
use App\Form\EligibiliteType;
use App\Form\DemandesMagasinType;
use App\Form\DemandesInternetType;
use App\Form\InscriptionType;

use App\Repository\CientsRepository;

use App\Entity\EligibiliteTest;
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
    public function index(Request $request, Mailer $mailer, GuardAuthenticatorHandler $guardHandler, FormLoginAuthenticator $authenticator, 
            UserPasswordEncoderInterface $passwordEncoder)
    {
        //On récupère les informations de la page d'accueil
        $post1 = $request->request->get('eligibilite');
        $em = $this->getDoctrine()->getManager();
        
        //On stocke les informations de la page d'accueil
        $session = $request->getSession();

         
        $user = $this->getUser();

        $test = new EligibiliteTest();
        $formE= $this->createForm(EligibiliteType::class, $test);

        $demande = new Demandes();
        $demande->setClient($user);

        $formMagasin = $this->createForm(DemandesMagasinType::class, $demande,[
           'validation_groups' => array('User', 'inscription'),
        ]);
         
        $formInternet = $this->createForm(DemandesInternetType::class, $demande,[
            'validation_groups' => array('User', 'inscription'),
         ]);
        
        if ($request->isMethod('POST')){
            if($request->request->has('demandes')){
                $form = $request->request->get('demandes');
                //$form['dateAchat'] = (\DateTime::createFromFormat('d-m-Y',$form['dateAchat']));

                if($user == null){

                    $repo = $em->getRepository(Clients::class);
                    $user = $repo->findOneBy(['email' => $form['client']['email']]);
                    $user->bis_construct($form['client']);  
                    
                }

                if($request->request->get('choix') == "internet"){
                        
                    $formInternet->submit($form);
                    $this->handle_form($em, $user, $demande, $formInternet, $request, $mailer, $guardHandler,  $authenticator, 
                        $passwordEncoder);
                }
                if($request->request->get('choix') == "magasin"){
                    $formMagasin->submit($form);
                    $this->handle_form($em, $user, $demande, $formMagasin, $request, $mailer, $guardHandler,  $authenticator, 
                    $passwordEncoder);
                }
                //$form->submit($request->request->get($form->getName()));
            }
            if($request->request->has('eligibilite')){
                $post1 = $request->request->get('eligibilite');
                $session->set('enseigne', $post1['enseigne']);
                $session->set('date_achat', $post1['date_achat']);
                $array_cat = array('High-Tech et électroménagers', 'Maison et jardin','Santé et beauté','Mode et sport');
                //var_dump($array_cat[$session->get('categorie')]);
                $session->set('categorie', $array_cat[$post1['categorie']-1]);
                $session->set('prix', $post1['prix']);
                $session->set('path', $post1['_token']);

            }
        }
        
        
        //$this->handle_form($user, $demande, $formInternet, $request, $mailer);
        //$this->handle_form($user, $demande, $formMagasin, $request, $mailer);
        
        return $this->render('enseigne/index.html.twig', [
            'form' => $formE->CreateView(), 
            'user' => $user,
        ]);
    }

    
    /**
     * @Route("/formulaire", name="formulaire")
     */
    public function form(Request $request, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $demande = new Demandes();
            $demande->setClient($user);
            $formMagasin = $this->createForm(DemandesMagasinType::class, $demande,[
                'validation_groups' => array('User', 'inscription'),
            ]);
            $formMagasin->handleRequest($request);

            return $this->render('enseigne/formulaire.html.twig', [
                'form1' => $formMagasin->CreateView(), 
                'user' => $user]);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/magasin", name="mag")
     */
    public function formMagasin(Request $request, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $demande = new Demandes();
            $demande->setClient($user);
            $formMagasin = $this->createForm(DemandesMagasinType::class, $demande,[
                'validation_groups' => array('User', 'inscription'),
            ]);
            $formMagasin->handleRequest($request);

            return $this->render('enseigne/magasin.html.twig', [
                'form1' => $formMagasin->CreateView(), 
                'user' => $user]);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }


    /**
     * @Route("/internet", name="internet")
     */
    public function formInternet(Request $request, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $user = $this->getUser();
            $demande = new Demandes();
            $demande->setClient($user);
            $formInternet = $this->createForm(DemandesInternetType::class, $demande);
            $formInternet->handleRequest($request); 

            return $this->render('enseigne/internet.html.twig', [
                'form1' => $formInternet->CreateView(), 
                'user' => $user]);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/pmembre", name="pmembre")
     */
    public function formInscript(Request $request, RouterInterface $router){
        //if($request->isXmlHttpRequest()){
            $demande = new Demandes();
            $formInternet = $this->createForm(DemandesInternetType::class, $demande);
            return $this->render('enseigne/inscription.html.twig', ['form1' => $formInternet->CreateView()]);
        //}
        /*else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }*/
    }

    /**
     * @Route("/felicitations", name="felicitation")
     */
    public function endForm(Request $request, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            return $this->render('enseigne/felicitation.html.twig');
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/membre", name="membre")
     */
    public function formIConnect(Request $request, RouterInterface $router){
        //if($request->isXmlHttpRequest()){
            $demande = new Demandes();
            $formInternet = $this->createForm(DemandesInternetType::class, $demande);
            return $this->render('enseigne/connexion.html.twig', ['form1' => $formInternet->CreateView()]);
        /*}
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }*/     
    }

    /**
     * @Route("/birth", name="birth")
     */
    public function birthDate(Request $request, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $demande = new Demandes();
            $formInternet = $this->createForm(DemandesInternetType::class, $demande);
            return $this->render('enseigne/naissance.html.twig', ['form1' => $formInternet->CreateView()]);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }


    public function handle_form($em, $user, $demande, $form, Request $request, 
                    Mailer $mailer, GuardAuthenticatorHandler $guardHandler, FormLoginAuthenticator $authenticator, 
                        UserPasswordEncoderInterface $passwordEncoder){
        $session = $request->getSession();
        $clientFile = null;
        if(($request->request->get('choix') == 'magasin')){
            $file = $request->files->get('demandes');
            $clientFile = $file['pieceJointe'];
        }

        $post = $request->request->get('demandes');
        
        if ($form->isSubmitted()){
            if($form->isValid()) {
            $status = array('status' => "success", "fileUploaded" => false);

            if (!is_null($clientFile)) {
                $originalFilename = pathinfo($clientFile->getClientOriginalName(), PATHINFO_FILENAME);
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = uniqid().'.'.$clientFile->guessExtension();

                try {
                    $clientFile->move(
                        $this->getParameter('upload_files_directory'),
                        $newFilename
                    );
                    $status = array('status' => "success", "fileUploaded" => true);
                } catch (FileException $e) {
                    
                }
                $demande->setPieceJointe($newFilename);
            }

            $dateAchat = \DateTime::createFromFormat('d-m-Y', $session->get('date_achat'));
            $path = "/factures/" . $session->get('path') . ".pdf";

            $demande->setPrixAchat($session->get('prix'));
            $demande->setCategorieProduit($session->get('categorie'));
            $demande->setEnseigne($session->get('enseigne'));            
            $demande->setFacture($path);
            $demande->setDateAchat($dateAchat);
            
            
            $repo = $em->getRepository(Clients::class);
            //$user = $repo->findOneBy(['email' => $post['client']['email']]);
            $demande->setClient($user);
            $em->persist($demande);
            $em->flush();

            $count = $demande->getId() + 1417;
            $demande->setNumeroDossier($count);
            $em->flush();

            
            $bodyMail = $mailer->createBodyMail('enseigne/mail2.html.twig', [ 'user' => $user,
                'demande' => $demande
            ]);
            $mailer->sendAdminMessage('hello@hedee.co', $demande->getClient()->getEmail(), 'Confirmation du dépot de dossier', $bodyMail);

            return $this->redirectToRoute('profil');
        }
    }
    }
}
