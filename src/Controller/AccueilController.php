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
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Categories;
use App\Entity\EligibiliteTest;

use App\Form\EligibiliteType;
use App\Services\Mailer;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $session->clear();

        return $this->render('accueil/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, Mailer $mailer)
    {
        $flashbag = $this->get('session')->getFlashBag();
        $flashbag->add("success", "Votre message a bien été envoyé");
        $flashbag->add("success", "Votre deuxième message a bien été envoyé");
        $session = $request->getSession();
        if($request->getMethod() == 'POST'){
            $post = $request->request;
            $expediteur = $post->get('mail');
            $message = $post->get('message');
            $objet = $post->get('objet');
            $mailer->sendMessage($expediteur, "bouyagui2@gmail.com", $objet , $message);
            $flashbag->add("success", "Votre message a bien été envoyé");
        }
        //$mailer->sendMessage('from@email.com', "jeremykihoulou@gmail.com", 'Confirmation de la création de votre compte Rembourseo', "Ceci est le corps du mail");


        return $this->render('utile/contact.html.twig');
    }

}
