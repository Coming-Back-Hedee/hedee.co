<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Form\DemandesMagasinType;

use App\Entity\Categories;
use App\Entity\Demandes;
use App\Entity\Enseignes;
use App\Entity\Magasins;

use App\Repository\CorrespCPVilleRepository;
use App\Repository\MagasinsRepository;
use App\Repository\MarquesRepository;
use App\Repository\EnseignesRepository;

/**
 * @Route("/utility")

 */
class AdminUtilityController extends AbstractController
{

    /**
     * @Route("/magasins", methods="GET", name="admin_utility_magasin")
     */
    public function getMagasinApi(Request $request, MagasinsRepository $magasinsRepository){

        //$session = $request->getSession();
        //$nom_enseigne = $session->get('enseigne');
        $nom_enseigne = 'auchan';
        $magasinsRepository = $this->getDoctrine()->getRepository(Magasins::class);
        $magasins = $magasinsRepository->findBy(['enseigne' => $nom_enseigne]);

        return $this->json([
            'magasins' => $magasins
        ], 200, [], []);
    }


    /**
     * @Route("/enseignes", methods="GET", name="admin_utility_enseigne")
     */
    public function getEnseignesApi(Request $request, EnseignesRepository $enseignesRepository){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseignes = $enseignesRepository->findAllMatching();

        return $this->json([
            'enseignes' => $enseignes
        ], 200, [], []);
    }
    
    /**
     * @Route("/categories", methods="GET", name="admin_utility_categorie")
     */
    public function getCategoriesApi(Request $request, EnseignesRepository $enseignesRepository){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseignes = $enseignesRepository->findAll();
        

        return $this->json([
            'enseignes' => $enseignes
        ], 200, [], []);
    }

    /**
     * @Route("/marques", methods="GET", name="admin_utility_marques")
     */
    public function getMarquesApi(Request $request, MarquesRepository $marquesRepository){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        $marques = $marquesRepository->findAllMatching();
        

        return $this->json([
            'marques' => $marques
        ], 200, [], []);
    }

    /**
     * @Route("/villes", methods="GET", name="admin_utility_villes")
     */
    public function getVillesApi(Request $request, CorrespCPVilleRepository $repository){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        $villes = $repository->findAll();
        

        return $this->json([
            'villes' => $villes
        ], 200, [], []);
    }
}