<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categories;

class EnseigneController extends AbstractController
{
    /**
     * @Route("/categorie-{nom_categorie}/{nom_enseigne}", name="enseigne", requirements={"nom_categorie" = "[a-z0-9-]+", "nom_enseigne" = "[a-z0-9-]+"})
     */
    public function index()
    {
    	$repo = $this->getDoctrine()->getRepository(Categories::class);

    	$categories =  $repo->findAll();
        return $this->render('enseigne/index.html.twig', [
            'controller_name' => 'EnseigneController',
            'categories' => $categories,
        ]);
    }
}
