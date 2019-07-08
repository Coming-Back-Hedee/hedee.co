<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Output\OutputInterface;

use App\Entity\Enseignes;

class PdfController extends AbstractController
{
    /**
     * @Route("/validation-{nom_enseigne}", name="facture", requirements={"nom_enseigne"= "[a-z0-9-]+"})
     */
    public function index(Request $request ,$nom_enseigne)
    {

        $nom_bdd = ucfirst($nom_enseigne);
        $repo = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repo->findOneBy(['nomEnseigne' => $nom_enseigne]);
        $nom = $enseigne->getNomEnseigne();

        //if($enseigne == null){
        //    return $this->render('utile/404_enseigne.html.twig');
        //}

        $session = $request->getSession();
        $post = $request->request->get('form');
                    
        $pdf = new \FPDF();
        $pdf->AddPage();
        //$pdf->Image('img/bg.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetFont('Arial','',16);
      
        $this->entete_facture($enseigne, $pdf, $post);      
        $this->cooordonnes_client($pdf, $post);
        $this->entete_table(120, $pdf);
        
        $this->details_table(128, $pdf, $post, $session);
        $this->footer($pdf);


        $this->rectangle($pdf, 10,100,40,10,'DF', 20, 30);
        $test1 = $pdf->Output('F', 'test.pdf');
        
        

        $dir = "C:/UwAmp/www/project-cb2019";
        $pdf_path = $dir . "/public/test.pdf";
        $jpeg = $dir . "/public/test.png";
        exec("magick convert $pdf_path -colorspace RGB -resize 800 $jpeg");

        $data = ['path' => $jpeg];
        
        //return new JsonResponse($data);

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));  
        
    }

    public function rectangle_w_title($pdf, $x, $y, $w, $h, $style, $x1_blank, $x2_blank, $title){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(255);
        $pdf->SetLineWidth(1);
        $pdf->Rect($x, $y, $w, $h, $style);
        $pdf->SetLineWidth(1.2);
        $pdf->SetDrawColor(255);
        $pdf->Line($x1_blank, $y, $x2_blank, $y);
        $pdf->Text($x1_blank+1, )
    }

    public function entete_facture($enseigne, $pdf, $post){
        $image = "../public/img/logo/" . $enseigne->getLogoEnseigne();
        $numCommande = $post['numero_commande'];

        $pdf->SetFont('Arial','',12);
        $pdf->Image($image, 10, 10, 33.78);
        $pdf->Text(18,68,utf8_decode("Numéro de commande: $numCommande"));
        $pdf->Text(18,73,utf8_decode("Date: du 27/09/2027"));
 
    }

    public function cooordonnes_client($pdf, $post){
        $nom = ucfirst($post['nom']);
        $prenom = ucfirst($post['prenom']);
        $telephone = $post['telephone'];
        $adresse = $post['adresse']['nom_rue'];
        $complements = $post['adresse']['complements'];
        $ville = ucfirst($post['adresse']['ville']);
        $code_postal = $post['adresse']['code_postal'];
        $pdf->Text(130,68,utf8_decode("$prenom $nom"));
        $pdf->Text(130,73,utf8_decode("$adresse"));
        $pdf->Text(130,78,utf8_decode("$code_postal $ville"));
        $pdf->Text(130,83,utf8_decode("$telephone"));
    }

    public function entete_table($position, $pdf){
        $pdf->SetDrawColor(183); // Couleur des filets
        $pdf->SetFillColor(221); // Couleur du fond
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetFont('Arial','B',16);
        $pdf->SetY($position);
        $pdf->SetX(8);
        $pdf->Cell(50,8,'Categorie',1,0,'C',1);
        $pdf->SetX(58); // 8 + 96
        $pdf->Cell(50,8,'Marque',1,0,'C',1);
        $pdf->SetX(108); // 104 + 10
        $pdf->Cell(50,8,'Reference',1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
    }
    public function details_table($position, $pdf, $post, $session){
        $categorie = $session->get('categorie');
        $marque = $post['marque'];
        $reference = $post['reference'];
        $pdf->SetDrawColor(183); // Couleur des filets
        $pdf->SetFillColor(255); // Couleur du fond
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetFont('Arial','',12);
        $pdf->SetY($position);
        $pdf->SetX(8);
        $pdf->Cell(50,8,utf8_decode("$categorie"),1,0,'C',1);
        $pdf->SetX(58); // 8 + 96
        $pdf->Cell(50,8,utf8_decode("$marque"),1,0,'C',1);
        $pdf->SetX(108); // 104 + 10
        $pdf->Cell(50,8,utf8_decode("$reference"),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
    }

    public function footer($pdf){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(100);
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetFont('Arial','B',16);
        $pdf->SetLeftMargin(0);
        $pdf->SetRightMargin(0);
        $pdf->SetLineWidth(2);
        $pdf->Rect(0,180, $pdf->GetPageWidth(), 116, 'DF');
        $pdf->SetX(0);
        $pdf->SetY(180);
        $pdf->Cell(0,8,utf8_decode('En cours de recherche...'),1,0,'C',1);
        $pdf->SetY(200);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(70,8,utf8_decode('Prix le moins cher constaté...'),1,0,'C',1);
        $pdf->SetX(140);
        $pdf->Cell(70,8,utf8_decode('Magasin le moins cher'),1,0,'C',1);
        $pdf->SetX(0);
        $pdf->SetY(230);
        $pdf->Cell(70,8,utf8_decode('Date constatée'),1,0,'C',1);
        $pdf->SetX($pdf->GetPageWidth()/2);
        $pdf->Cell($pdf->GetPageWidth()/2,8,utf8_decode('Conseils'),1,0,'C',1);


        $pdf->Ln(); // Retour à la ligne
    }
}
