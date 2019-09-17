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
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;

use App\Entity\Categories;
use App\Entity\Demandes;
use App\Entity\EligibiliteTest;

use App\Repository\DemandesRepository;

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
        $check_path = "/factures/" . $session->get('path');
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $to_delete = $repo->findBy(['facture' => $check_path]);
        /*if(!$to_delete){

        }*/

        //var_dump($session->get('path'));
        $session->clear();

        return $this->render('accueil/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, Mailer $mailer)
    {
        /*$attachment = 'factures/vv-vl7ByNv5O70KncOcXBe_-wy7mw1Uk4K4hguRM5cI.pdf';
        $pdf = new FPDI();
        //$path_pdf = "/factures/" . $attachement;
        $this->forward('App\Controller\PdfController::recup_pdf', ['pdf'  => $pdf,  'path'  => $attachment]);
        $mailer->sendAdminMessage("skm.jeremy@gmail.com", "davidslk230@hotmail.fr", "test" , "Ceci est un test", $pdf->Output('', 'S'));*/
        $flashbag = $this->get('session')->getFlashBag();
        $session = $request->getSession();
        if($request->getMethod() == 'POST'){
            $post = $request->request;
            $expediteur = $post->get('mail');
            $message = $post->get('message');
            $objet = $post->get('objet');
            $mailer->sendMessage($expediteur, "bouyagui@hedee.co", $objet , $message);
            $flashbag->add("success", "Votre message a bien été envoyé");
        }

        return $this->render('utile/contact.html.twig');
    }

    /*
     * @Route("/test/{id}", name="recap", requirements={"id"="\d+"})
     
    public function recapitulatif(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $dossier = $repo->findOneBy(["numeroDossier" => $id]);
        if(!$dossier || $dossier->getClient() != $user){
            throw $this->createNotFoundException('');
        }

        return $this->render('test.html.twig', ['dossier' => $dossier]);

    }
    */
}
