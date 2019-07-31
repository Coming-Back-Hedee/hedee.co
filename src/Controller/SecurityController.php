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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function login(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, 
                        UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider)
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
            
        return $this->render('security/modal.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView(),
        ));
    }

    /**
     * @Route("/connexion2", name="connexion2")
     */
    public function login2(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, 
                        UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider)
    {
        $isAvailable = false;
        $post = $request->request;
        $repo = $this->getDoctrine()->getRepository(Clients::class);
        $user =  $repo->findOneBy(['email' => $post->get('_username')]);
        if($user != null){
            if ($post->has('_password')){
                $plainPassword = $post->get('_password');
                if($passwordEncoder->isPasswordValid($user, $plainPassword)){
                    $isAvailable = true;
                }
            }
            else{
                $isAvailable = true;
            } 
        }           
        $data = $isAvailable;   
        return new JsonResponse($data);
    }

}
