<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\TestEligibilite;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        $method = $request->getMethod();
        $user = $this->getUser();
        //var_dump($user->getId());
        $form = new TestEligibilite();
        
        $form = $this->createFormBuilder($form, ['attr' => ['id' => 'form-eligibilite']])
            ->add('categorie', TextType::class, ['label' => 'Catégorie du produit'])
            ->add('enseigne', TextType::class, ['label' => 'Magasin d\'achat'])
            ->add('date_achat', TextType::class, ['label' => 'Date d\'achat'])       
            ->add('prix', MoneyType::class, ['label' => 'Prix de l\'article'])
            ->add('remise', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'label' => 'Avez-vous bénéficié d\'une remise', 'required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Continuer la démarche'])
            
            ->setAction('/demande-remboursement')
            ->getForm();


        return $this->render('accueil/index2.html.twig', [
            'form' => $form->CreateView(),
            'user' => $user,           
        ]);
    }
}
