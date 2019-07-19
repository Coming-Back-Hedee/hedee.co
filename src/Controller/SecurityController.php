<?php

namespace App\Controller;

use App\Form\InscriptionType;

use App\Entity\Clients;
use App\Services\Mailer;
use App\Security\FormLoginAuthenticator;

use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function login(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, UserPasswordEncoderInterface $passwordEncoder)
    {

        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('profil');
        }


        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('profil');
        }
        $user = new Clients();
        // instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que la saisie du mot de passe soit obligatoire
        $form = $this->createForm(InscriptionType::class, $user,[
           'validation_groups' => array('User', 'inscription'),
        ]);        

        $form->handleRequest($request);
        $repo = $this->getDoctrine()->getRepository(Clients::class);
        $email =  $repo->findOneBy(['email' => $user->getEmail()]);
        if($email != null){
            //var_dump($enseigne);
            $session = $request->getSession();
            $session->getFlashBag()->add('warning', "Cette adresse email est déjà utilisé.");
            return $this->redirectToRoute('inscription');
        }

        $bodyMail = $mailer->createBodyMail('inscription/mail2.html.twig', [
            'user' => $user
        ]);
        

        if ($form->isSubmitted() && $form->isValid()) {
 
            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //$user->setEmail($form['email']);
 
            // Enregistre le membre en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $mailer->sendMessage('from@email.com', $user->getEmail(), 'Confirmation de la création de votre compte Rembourseo', $bodyMail);
 
        }

        return $this->render('security/modal.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView(),
        ));
    }
}
