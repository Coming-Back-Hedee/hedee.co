<?php

namespace App\Controller;

use App\Entity\Demandes;
use App\Entity\AlertePrix;
use App\Form\AlerteType;
use App\Repository\DemandesRepository;

use App\Services\Mailer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $demandes = $repo->findAll();

        return $this->render('admin/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * @Route("/admin/dossier-{numeroDossier}", name="dossier", requirements={"page"="\d+"})
     */
    public function dossierClient(Request $request, Mailer $mailer, $numeroDossier)
    {
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
        $dossier = $repo->findOneBy(["numeroDossier" => $numeroDossier]);
        if ($dossier == null){
            return $this->render('enseigne/404_enseigne.html.twig');
        }
        $alerte = new AlertePrix();
        $alerte->setDossier($dossier);
        var_dump($alerte->getDossier()->hasAlertesPrix());
        $form= $this->createForm(AlerteType::class, $alerte);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        //if ($request->getMethod() == 'POST'){
            $em = $this->getDoctrine()->getManager();
            $dir = getcwd();

            // on récupère le récapitulatif 'd'achat du client
            $pdf = new FPDI();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetFooterMargin(0);
            $pdf->SetAutoPageBreak(FALSE);
            $pdf->setFontSubsetting(TRUE);           
            $pdf->setSourceFile($dir . $dossier->getFacture());
            $templateId = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

            //On modifie le récapitulatif
            $this->forward('App\Controller\PdfController::footer', ['pdf'  => $pdf, 'texte' => 'Alerte de prix']);
            $this->forward('App\Controller\PdfController::details_footer', [
                'pdf'  => $pdf,
                'alerte' => $alerte, 
                'session' => $request->getSession(),
                'reset' => $alerte->getDossier()->hasAlertesPrix(),
                ]);

            $path_pdf = $dir . $dossier->getFacture();
            $test1 = $pdf->Output($path_pdf, 'F');
            $dossier->setStatut('Alerte prix');
            $em = $this->getDoctrine()->getManager();
            $em->persist($alerte);
            $em->flush();

            $bodyMail = $mailer->createBodyMail('admin/mail.html.twig', ['dossier' => $dossier]);
            $mailer->sendMessage('from@email.com', $dossier->getClient()->getEmail(), 'Alerte baisse de prix', $bodyMail);

        }

        return $this->render('admin/dossier_client.html.twig', [
            'alerte' => $alerte,
            'form' => $form->CreateView(),
        ]);
    }

    /**
     * @Route("/admin/all_demandes", methods="GET", name="admin_all_demandes")
     */
    public function getAllDemandes(Request $request, DemandesRepository $demandesRepository){
        
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

    /**
     * @Route("/admin/demandes_en_cours", methods="GET", name="admin_demandes_en_cours")
     */
    public function getDemandesEnCours(Request $request, DemandesRepository $demandesRepository){
        
        $demandes = $demandesRepository->findBy(['statut' => 'En cours']);
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

    /**
     * @Route("/admin/demandes_remboursees", methods="GET", name="admin_demandes_r")
     */
    public function getDemandesRemboursees(Request $request, DemandesRepository $demandesRepository){
        
        $demandes = $demandesRepository->findBy(['statut' => 'Remboursé']);
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

    /**
     * @Route("/admin/demandes_non_remboursees", methods="GET", name="admin_demandes_nr")
     */
    public function getDemandesNonRemboursees(Request $request, DemandesRepository $demandesRepository){
        
        $demandes = $demandesRepository->findBy(['statut' => 'Non remboursé']);
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

    /**
     * @Route("/admin/demandes_alerte", methods="GET", name="admin_demandes_alerte")
     */
    public function getDemandesAlerte(Request $request, DemandesRepository $demandesRepository){
        
        $demandes = $demandesRepository->findBy(['statut' => 'Alerte prix']);
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

    /**
     * @Route("/admin/client_demandes", methods="GET", name="client_demandes")
     */
    public function getClientDemandes(Request $request, DemandesRepository $demandesRepository){
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
}
