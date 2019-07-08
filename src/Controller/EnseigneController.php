<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Form\AdresseType;
use App\Entity\Adresses;
use App\Entity\Enseignes;
use App\Entity\Formulaire;
use App\Entity\Demandes;


class EnseigneController extends AbstractController
{
    /**
     * @Route("/demande-remboursement", name="enseigne")
     */
    public function index(Request $request)
    {
        //On récupère les informations de la page d'accueil
        $post = $request->request->get('form');
        
        //On stocke les informations de la page d'accueil
        $session = $request->getSession();
        if($post != null){    
            
            $session->set('enseigne', $post['enseigne']);
            $session->set('date_achat', $post['date_achat']);
            $session->set('categorie', $post['categorie']);
            $session->set('prix', $post['prix']);
            $session->set('remise', $post['remise']);
        }

        //On vérifie que le client est connecté
        if(!$this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('connexion');
        }
        
        //On vérifie que le client a entré une enseigne valide
        $nom_bdd = ucfirst($session->get('enseigne'));
        $repo = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repo->findOneBy(['nomEnseigne' => $nom_bdd]);
        
        $user = $this->getUser();

        if($enseigne == null){
            return $this->render('enseigne/404_enseigne.html.twig');
        }
        
        //var_dump($session->get('prix')); 0= false 1= true

        $form = new Formulaire();

        $form = $this->createFormBuilder($form, ['attr' => ['id' => 'form-enseigne']])
        //informations de la commande
            ->add('magasin', TextType::class, ['label' => 'Magasin d\'achat'])
            ->add('marque', TextType::class, ['label' => 'Marque du produit'])
            ->add('reference', TextType::class, ['label' => 'Référence du produit'])
            ->add('numero_commande', TextType::class,   ['label' => 'Numéro de commande',
            'help' => 'Le numéro de commande se trouve sur votre facture',
            'required' => false
            ])
            ->add('commentaires', TextType::class,   ['label' => 'Commentaires',
            'help' => 'Vous pouvez renseigner ici toutes informations complémentaires',
            'required' => false
            ])
            //coordonnées du client
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('mail', EMailType::class, ['label' => 'Adresse mail'])
            ->add('telephone', NumberType::class, ['label' => 'Numéro de téléphone'])
            ->add('adresse', AdresseType::class, ['label' => 'Adresse'])

            ->add('submit', SubmitType::class, ['label' => 'Valider'])
            ->getForm();            

        return $this->render('enseigne/index.html.twig', [
            'enseigne' => $enseigne,
            'post' => $post,
            'form' => $form->CreateView(), 
            'user' => $user,
        ]);
    }
}
