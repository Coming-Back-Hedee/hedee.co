<?php
namespace App\Controller;
 
use App\Form\InscriptionType;
use App\Security\FormLoginAuthenticator;

use App\Entity\Clients;
use App\Services\Mailer;

use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

 
class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registerAction(Request $request, Mailer $mailer, GuardAuthenticatorHandler $guardHandler, 
                                    FormLoginAuthenticator $authenticator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $session = $request->getSession();
        // création du formulaire
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
            $session->getFlashBag()->add('warning', "Cette adresse email est déjà utilisée.");
            return $this->redirectToRoute('inscription');
        }

        $bodyMail = $mailer->createBodyMail('inscription/mail2.html.twig', [
            'user' => $user
        ]);
        

        if ($form->isSubmitted() && $form->isValid()) {
                     
            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //$user->setCodeParrainage();
            
            if($request->request->get('inscription')['codeParrainage'] != ""){ 
                $codeP = $request->request->get('inscription')['codeParrainage'];
                          
                $parrain = $repo->findOneBy(['codeParrainage' => $codeP]);
                if($parrain == null){
                    $session->getFlashBag()->add('warning', "Le code parrainage renseigné n'est associé à aucun membre.");
                    return $this->redirectToRoute('inscription');
                }
                else{
                    $idParrain = $parrain->getId();
                    $session->getFlashBag()->add('success', "Le code parrainage est $idParrain.");
                    $user->setIdParrain($idParrain);
                }
            }
            
            // Enregistre le membre en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $user2 = $repo->findOneBy(['email' => $user->getEmail()]);
            

            $user2->setCodeParrainage();                
            $em->persist($user);
            $em->flush();
            
            $mailer->sendMessage('from@email.com', $user->getEmail(), 'Confirmation de la création de votre compte Rembourseo', $bodyMail);
            
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main');
        }
 
        return $this->render(
            'inscription/index.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/inscription2", name="inscription2")
     */
    public function inscription2(Request $request, Mailer $mailer, GuardAuthenticatorHandler $guardHandler, 
                            FormLoginAuthenticator $authenticator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $post = $request->request;
        $repo = $this->getDoctrine()->getRepository(Clients::class);

        $email =  $repo->findOneBy(['email' => $post->get('inscription')['email']]);
        if($email == null){
            $isAvailable = true;
        }
        else{
            $isAvailable = false;
        }
          
    $data = $isAvailable;   
    return new JsonResponse($data);
    }
}
