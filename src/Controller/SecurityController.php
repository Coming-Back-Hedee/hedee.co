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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
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
            
        return $this->render('security/index.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView(),
        ));
    }
    /**
     * @Route("/connexion2", name="connexion2")
     */
    public function login2(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, 
                        UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider, RouterInterface $router)
    {
        if($request->isXmlHttpRequest()){
            $isAvailable = false;
            $post = $request->request;
            $repo = $this->getDoctrine()->getRepository(Clients::class);
            
            $user =  $repo->findOneBy(['email' => $post->get('_username')]);
            if($user != null){
                if($post->has('_password')){
                    $plainPassword = $post->get('_password');
                    if($passwordEncoder->isPasswordValid($user, $plainPassword)){
                        $isAvailable = true;
                    }
                }
                else
                    $isAvailable = true;
            }           
            $data = $isAvailable;   
            return new JsonResponse($data);
        }
        else{
            $url = $router->generate('accueil');
            return new RedirectResponse($url);
        }
    }
    /**
     * @Route("/connexion3", name="connexion3")
     */
    public function login3(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, 
                        UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider, RouterInterface $router)
    {
        if($request->isXmlHttpRequest()){
            $post = $request->request;
            $repo = $this->getDoctrine()->getRepository(Clients::class);
            
            $user =  $repo->findOneBy(['email' => $post->get('_username')]); 
            $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonObject = $serializer->serialize($user, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
        }
        else{
            $url = $router->generate('accueil');
    
            return new RedirectResponse($url);
        }
    }
    /**
     * @Route("/connexion4", name="connexion4")
     */
    public function login4(Request $request, Mailer $mailer, AuthenticationUtils $authUtils, 
                        UserPasswordEncoderInterface $passwordEncoder, UserProviderInterface $userProvider, RouterInterface $router)
    {
        if($request->isXmlHttpRequest()){
            $post = $request->request;
            $repo = $this->getDoctrine()->getRepository(Clients::class);
            
            $user =  $repo->findOneBy(['email' => $post->get('_username')]);
            // Here, "public" is the name of the firewall in your security.yml
            $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            // For older versions of Symfony, use security.context here
            $this->get("security.token_storage")->setToken($token);
            // Fire the login event
            // Logging the user in above the way we do it doesn't do this automatically
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            //$url = $this->router->generate('profil');
            //return new RedirectResponse($url);
            return new JsonResponse(true);
        }
        else{
            $url = $router->generate('accueil');
            return new RedirectResponse($url);
        }
    }
}