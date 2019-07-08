<?php

namespace App\Controller;

use App\Entity\Clients;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="espace_client")
     */
    public function demandes()
    {
        $user = $this->getUser();
        $test = $user->getEmail()[0] . $user->getId();
        var_dump($test);
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/informations-generales", name="info_client")
     */
    public function informations()
    {
        $user = $this->getUser();
        return $this->render('profil/informations.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }
}
