<?php

namespace App\Controller;

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
        $demandes = $repo->findBy(['client' => $user]);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'demandes' => $demandes,
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

        if ($request->isMethod('POST')){
            $mode = new ModeVersement();
            $post = $request->request->get('mode_versement');
            $mode->setSwiftBic(strtoupper($post['swiftBic']));
            $mode->setIban(strtoupper($post['iban']));
            $mode->setProprietaire($post['proprietaire']);
            $user->setModeVersement($mode);
            $em->flush();

            $url = $router->generate('profil');

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
    public function informations(Request $request, RouterInterface $router )
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(InfoClientType::class, $user);

        if ($request->isMethod('POST')){
            $post = $request->request->get('info_client');
            if(array_key_exists('nom', $post)){
                $user->setNom(ucfirst($post['nom']));
                $user->setPrenom(ucfirst($post['prenom']));
                
            }
            if(array_key_exists('dateNaissance', $post)){
                $string = $post['dateNaissance']['day'] . "-" ;
                $string .=  $post['dateNaissance']['month'] . "-" . $post['dateNaissance']['year'];
                $dateNaissance = \DateTime::createFromFormat('d-m-Y',$string);
                $user->setDateNaissance($dateNaissance);
            }
            if(array_key_exists('numeroTelephone', $post)){
                $user->setNumeroTelephone($post['numeroTelephone']);
            }
            if(array_key_exists('adresse', $post)){
                $user->getAdresse()->bis_construct($post['adresse']);
            }
            $em->flush();
            $url = $router->generate('profil');

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
        //if($request->isXmlHttpRequest()){
            $form = $this->createForm(ModeVersementType::class);
            return $this->render('profil/m_versement.html.twig', ['form' => $form->CreateView()]);
        /*}
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }*/
    }

}
