<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Demandes;
use App\Repository\DemandesRepository;

use App\Form\InfoClientType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="profil")
     */
    public function demandes(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $session->clear();
        
        
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $demandes = $repo->findBy(['client' => $user]);

        $session->set('test', $demandes);

        //var_dump($user->getAdresse());
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }
   
    /**
     * @Route("/remboursement", name="remboursement")
     */
    public function remboursements(Request $request)
    {
        $user = $this->getUser();
        return $this->render('profil/remboursement.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }
    
    /**
     * @Route("/parrainage", name="parrainage")
     */
    public function parrainages(Request $request)
    {
        $user = $this->getUser();
        return $this->render('profil/parrainage.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/informations-generales", name="info_client")
     */
    public function informations(Request $request)
    {

        $user = $this->getUser();
        $form = $this->createForm(InfoClientType::class, $user,[
            'validation_groups' => array('User', 'inscription'),
         ]);     
        return $this->render('profil/informations.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'form' => $form->CreateView(),
        ]);
    }

}
