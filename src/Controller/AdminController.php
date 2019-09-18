<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Demandes;
use App\Entity\AlertePrix;

use App\Form\ClotureType;
use App\Form\AlerteType;

use App\Services\Mailer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    private $dispatcher;

    /**
     * @Route("/connexion", name="admin_connect")
     */
    public function connexion(Request $request, AuthenticationUtils $authUtils, 
                    UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider, RouterInterface $router)
    {
        $admin = ["bouyagui@hedee.co", "skm.jeremy@gmail.com"];
        $isAvailable = false;
        $post = $request->request;
        $repo = $this->getDoctrine()->getRepository(Clients::class);
        $lastUsername = "";
        $session = $request->getSession();

        if($this->getUser() != null){
            if(!in_array("ROLE_ADMIN", $this->getUser()->getRoles())){
                $url = $router->generate('accueil');

                return new RedirectResponse($url);
            }
        }
        $nb_admin = $repo->findByRole("ROLE_ADMIN");

        
        if($request->getMethod() == 'POST'){        
            $user =  $repo->findOneBy(['email' => $post->get('_username')]);
            if($user != null){
                if($post->has('_password')){
                    $plainPassword = $post->get('_password');
                    if(in_array("ROLE_ADMIN", $user->getRoles()) && $passwordEncoder->isPasswordValid($user, $plainPassword)){
                    // Here, "public" is the name of the firewall in your security.yml
                        $token = new UsernamePasswordToken($user, $plainPassword, "main", $user->getRoles());

                        // For older versions of Symfony, use security.context here
                        $this->get("security.token_storage")->setToken($token);

                        // Fire the login event
                        // Logging the user in above the way we do it doesn't do this automatically
                        $event = new InteractiveLoginEvent($request, $token);
                        $this->dispatcher = new EventDispatcher();
                        $this->dispatcher->dispatch("security.interactive_login", $event);
                        return $this->redirectToRoute('admin');
                    }
                    else{
                        // get the login error if there is one
                        $error = "Connexion non autorisée";
                        $flashbag = $this->get('session')->getFlashBag();

                        // Add flash message
                        $flashbag->add("warning", $error);
                        

                        // last username entered by the user
                        $lastUsername = $post->get('_username');

                        return $this->render('admin/connexion.html.twig', array(
                            'last_username' => $lastUsername
                        ));
                    }
                }
            }
            else{
                if(in_array($post->get('_username'), $admin)){
                    $user = new Clients();        
                    $user->setEmail($post->get('_username'));
                    $session->set("usermail", $post->get('_username'));
                    $password = $passwordEncoder->encodePassword($user, $post->get('_password'));
                    $user->setRoles(["ROLE_ADMIN"]);
                    $user->setPassword($password);
                    $flashbag = $this->get('session')->getFlashBag();
                    $this->addFlash("success_hedee", "Veuillez confirmer votre mot de passe..");
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('confirm');
                }
            }
            
        }             
        return $this->render('admin/connexion.html.twig', array(
            'last_username' => $lastUsername
        ));
    }

    /**
     * @Route("/confirmation", name="confirm")
     */
    public function confirmation(Request $request, UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router){
        $session = $request->getSession();
        $repo = $this->getDoctrine()->getRepository(Clients::class);
        $user =  $repo->findOneBy(['email' => $session->get('usermail')]);

        return $this->render('admin/inscription.html.twig', array(
            'last_username' => $user->getEmail()
        ));
    }

    /**
     * @Route("/", name="admin")
     */
    public function index(Request $request, RouterInterface $router)
    {
        //$repo = $this->getDoctrine()->getRepository(Demandes::class);
        //$demandes = $repo->findAll();
        if(!$this->isGranted('ROLE_ADMIN')){
            $url = $router->generate('admin_connect');

            return new RedirectResponse($url);
        }    
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/dossier-{numeroDossier}", name="dossier", requirements={"numeroDossier"="\d+"})
     */
    public function dossierClient(Request $request, Mailer $mailer, $numeroDossier)
    {
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $dossier = $repo->findOneBy(["numeroDossier" => $numeroDossier]);
        if ($dossier == null){
            return $this->render('enseigne/404_enseigne.html.twig');
        }
        $flashbag = $this->get('session')->getFlashBag();

        $alerte = new AlertePrix();
        $alerte->setDossier($dossier);

        $form1= $this->createForm(AlerteType::class, $alerte);
        $form2= $this->createForm(ClotureType::class, $alerte);

        $form1->handleRequest($request);
        $form2->handleRequest($request);

        $pdf = null;

        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $pdf = new FPDI();
            $path_pdf = getcwd() . $dossier->getFacture();
            $post = $request->request;
            $this->forward('App\Controller\PdfController::recup_pdf', ['pdf'  => $pdf,  'path'  => $path_pdf]);


            if ($form2->isSubmitted() && $form2->isValid()) {
                $montant = $post->get('cloture')['montantCloture'];
                if(array_key_exists('clotureNR', $post->get('cloture'))){
                    $statut = 'Non remboursé';
                    $mail_objet = "Alerte de non remboursement";
                                        
                    $dossier->setStatut('Non remboursé');
                    $em->flush();
                    $bodyMail = $mailer->createBodyMail('admin/mail_nremboursement.html.twig', ['dossier' => $dossier]);
                    
                }

                if(array_key_exists('clotureR', $post->get('cloture'))){
                    $statut = 'Remboursé';
                    $mail_objet = "Alerte de remboursement";

                    $dossier->setStatut('Remboursé');
                    $dossier->setMontantRemboursement($montant);
                    $em->flush();
                    $bodyMail = $mailer->createBodyMail('admin/mail_remboursement.html.twig', [
                        'dossier' => $dossier
                        ]);
                }
                $flashbag->add("success", "Email bien envoyé");

            }
            
            if ($form1->isSubmitted() && $form1->isValid()) {
                $statut = "Baisse de prix";
                $mail_objet = "Alerte baisse de prix";             

                //On modifie le récapitulatif
                $pdf = $facture->alerte($repo, $dossier->getId(), $alerte);
                /*$this->forward('App\Controller\PdfController::footer', ['pdf'  => $pdf]);
                $this->forward('App\Controller\PdfController::details_footer', [
                    'pdf'  => $pdf,
                    'alerte' => $alerte, 
                    'session' => $request->getSession(),
                    ]);*/
                $dossier->setStatut('Alerte prix');
                
                $em->persist($alerte);
                $em->flush();
                $bodyMail = $mailer->createBodyMail('admin/mail_alerte.html.twig', ['dossier' => $dossier]);
                $flashbag->add("success", "Alerte bien envoyée");
            }
            //$test1 = $pdf->Output($path_pdf, 'F');
            
            $mailer->sendAdminMessage('hello@hedee.co', $dossier->getClient()->getEmail(), $mail_objet, $bodyMail, $pdf);
        }

        return $this->render('admin/dossier_client.html.twig', [
            'alerte' => $alerte,
            'form' => $form1->CreateView(),
            'formC' => $form2->CreateView(),
        ]);
    }
}
