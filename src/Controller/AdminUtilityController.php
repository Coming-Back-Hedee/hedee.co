<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Form\DemandesMagasinType;

use App\Entity\Categories;
use App\Entity\Demandes;
use App\Entity\Enseignes;
use App\Entity\Magasins;

use App\Repository\CorrespCPVilleRepository;
use App\Repository\MagasinsRepository;
use App\Repository\MarquesRepository;
use App\Repository\EnseignesRepository;
use App\Repository\DemandesRepository;

/**
 * @Route("/utility")

 */
class AdminUtilityController extends AbstractController
{

    /**
     * @Route("/magasins", methods="GET", name="admin_utility_magasin")
     */
    public function getMagasinApi(Request $request, MagasinsRepository $magasinsRepository, RouterInterface $router){

        //$session = $request->getSession();
        //$nom_enseigne = $session->get('enseigne');
        if($request->isXmlHttpRequest()){
            $nom_enseigne = 'auchan';
            $magasinsRepository = $this->getDoctrine()->getRepository(Magasins::class);
            $magasins = $magasinsRepository->findBy(['enseigne' => $nom_enseigne]);

            return $this->json([
                'magasins' => $magasins
            ], 200, [], []);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }


    /**
     * @Route("/enseignes", methods="GET", name="admin_utility_enseigne")
     */
    public function getEnseignesApi(Request $request, EnseignesRepository $enseignesRepository, RouterInterface $router){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        if($request->isXmlHttpRequest()){
            $enseignes = $enseignesRepository->findAllMatching();

            return $this->json([
                'enseignes' => $enseignes
            ], 200, [], []);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }
    
    /**
     * @Route("/categories", methods="GET", name="admin_utility_categorie")
     */
    public function getCategoriesApi(Request $request, EnseignesRepository $enseignesRepository, RouterInterface $router){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        if($request->isXmlHttpRequest()){
            $enseignes = $enseignesRepository->findAll();
            

            return $this->json([
                'enseignes' => $enseignes
            ], 200, [], []);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/marques", methods="GET", name="admin_utility_marques")
     */
    public function getMarquesApi(Request $request, MarquesRepository $marquesRepository, RouterInterface $router){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        if($request->isXmlHttpRequest()){
            $marques = $marquesRepository->findAllMatching();
            

            return $this->json([
                'marques' => $marques
            ], 200, [], []);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/villes", methods="GET", name="admin_utility_villes")
     */
    public function getVillesApi(Request $request, CorrespCPVilleRepository $repository, RouterInterface $router){

        //$enseignesRepository = $this->getDoctrine()->getRepository(Enseignes::class);
        if($request->isXmlHttpRequest()){
            $villes = $repository->findAll();
            

            return $this->json([
                'villes' => $villes
            ], 200, [], []);
        }
        else{
            $url = $router->generate('accueil');

            return new RedirectResponse($url);
        }
    }

    /**
     * @Route("/all_demandes", methods="GET", name="admin_all_demandes")
     */
    public function getAllDemandes(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findAll();
            $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/all_demandes_reverse", methods="GET", name="admin_all_demandes_reverse")
     */
    public function getAllDemandesRe(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findAllReverse();
            $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/demandes_en_cours", methods="GET", name="admin_demandes_en_cours")
     */
    public function getDemandesEnCours(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findByReverse('En cours');
            $encoders = [new JsonEncoder()]; 
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/demandes_remboursees", methods="GET", name="admin_demandes_r")
     */
    public function getDemandesRemboursees(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findByReverse('Remboursé');
            $encoders = [new JsonEncoder()]; 
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/demandes_non_remboursees", methods="GET", name="admin_demandes_nr")
     */
    public function getDemandesNonRemboursees(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findByReverse('Non remboursé');
            $encoders = [new JsonEncoder()]; 
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/demandes_alerte", methods="GET", name="admin_demandes_alerte")
     */
    public function getDemandesAlerte(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $demandes = $demandesRepository->findByReverse('Alerte prix');
            $encoders = [new JsonEncoder()]; 
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/client_demandes", methods="GET", name="client_demandes")
     */
    public function getClientDemandes(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
            $client = $this->getUser();
            $demandes = $demandesRepository->findClientReverse($client->getId());
            $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $jsonObject = $serializer->serialize($demandes, 'json', [
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
     * @Route("/client_demandes_reverse", methods="GET", name="client_demandes_reverse")
     */
    public function getClientDemandesReverse(Request $request, DemandesRepository $demandesRepository, RouterInterface $router){
        if($request->isXmlHttpRequest()){
        $client = $this->getUser();
        $demandes = $demandesRepository->findBy(["client" => $client]);
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonObject = $serializer->serialize($demandes, 'json', [
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
}