<?php
namespace App\Controller;
 
use App\Form\ClientType;

use App\Entity\Clients;
use App\Services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

 
class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function registerAction(Request $request, Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        // création du formulaire
        $user = new Clients();
        // instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que la saisie du mot de passe soit obligatoire
        $form = $this->createForm(ClientType::class, $user,[
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
 
            return $this->redirectToRoute('accueil');
        }
 
        return $this->render(
            'inscription/index.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/inscription2", name="inscription_demande")
     */
    public function registerAction2(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // création du formulaire
        $user = new Clients();
        // instancie le formulaire avec les contraintes par défaut, + la contrainte registration pour que la saisie du mot de passe soit obligatoire
        $form = $this->createForm(ClientType::class, $user,[
           'validation_groups' => array('User', 'inscription'),
        ]);        

        $form->handleRequest($request);
        var_dump($user->getEmail());
        $repo = $this->getDoctrine()->getRepository(Clients::class);
        $email =  $repo->findOneBy(['email' => $user->getEmail()]);
        if($email != null){
            //var_dump($enseigne);
            $session = $request->getSession();
            $session->getFlashBag()->add('warning', "Cette adresse email est déjà utilisé.");
            return $this->redirectToRoute('inscription');
        }

        $bodyMail = $mailer->createBodyMail('inscription/mail.html.twig', [
            'user' => $user
        ]);
        if ($form->isSubmitted() && $form->isValid()) {
 
            // Encode le mot de passe
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //$user->setEmail($form['email']);
            var_dump($user);
 
            // Enregistre le membre en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
 
            return $this->redirectToRoute('enseigne');
        }
 
        return $this->render(
            'inscription/index.html.twig',
            ['form' => $form->createView()]
        );
    }
}
