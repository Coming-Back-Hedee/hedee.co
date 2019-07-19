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
     * @Route("/facture", name="facture")
     */
    public function index(Request $request)
    {

        $session = $request->getSession();
        $nom_enseigne = $session->get('enseigne');

        if($request->request->has('demandes_internet')){
            $post = $request->request->get('demandes_internet');
            $session->set('choix', 'internet');
        }
        else{
            $post = $request->request->get('demandes_magasin');
            $session->set('choix', 'magasin');
        }
      

        $nom_bdd = ucfirst($nom_enseigne);
        $repo = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repo->findOneBy(['nomEnseigne' => $nom_enseigne]);
        $nom = $enseigne->getNomEnseigne();

        //if($enseigne == null){
        //    return $this->render('utile/404_enseigne.html.twig');
        //}

        
        
                    
        $pdf = new \FPDF();
        $pdf->AddPage();
        //$pdf->Image('img/bg.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetFont('Arial','',16);
      
        $this->entete_facture($enseigne, $pdf, $post, $session);      
        $this->cooordonnes_client($pdf, $post);

        $this->rectangle_w_title($pdf, 25,100,40,30,'DF', 30, 47, utf8_decode("Catégorie") );
        if($session->get('choix') == 'magasin'){
            $this->rectangle_w_title($pdf, 85,100,40,30,'DF', 90, 103, "Marque" );
            $this->rectangle_w_title($pdf, 145,100,40,30,'DF', 150, 168, utf8_decode("Référence"));
        }
        else{
            $this->rectangle_w_title($pdf, 85,100, 120,30,'DF', 90, 98, "URL" );
        }
        
        $this->rectangle_w_title($pdf, 135,150,40,30,'DF', 140, 156, utf8_decode("Prix payé"));
        $this->rectangle_w_title($pdf, 15,210,45,30,'DF', 17, 57, utf8_decode("Enseigne la moins chère") );
        $this->rectangle_w_title($pdf, 82.5,210,45,30,'DF', 85, 111, "Date du constat" );
        $this->rectangle_w_title($pdf, 150,210,45,30,'DF', 152, 181, utf8_decode("Prix le moins cher"));
        $this->rectangle_w_title($pdf, 35,260,140,30,'DF', 40, 55, utf8_decode("Conseils"));
        $this->details_table(115, $pdf, $post, $session);
        $this->footer($pdf);
        $dir = "C:/UwAmp/www/project-cb2019";
        $path_pdf = $dir . "/public/facture" . $session->get('numDossier') . ".pdf";
        $test1 = $pdf->Output('F', $path_pdf);
        

        
        //$pdf_path = $dir . "/public/facture" . $session->get('numeroDossier') ."pdf";
        $jpeg = $dir . "/public/facture" . $session->get('numDossier') .".png";
        exec("magick convert $path_pdf -colorspace RGB -resize 800 $jpeg");

        $data = ['path' => $jpeg];
        
        return new JsonResponse($data);

        //return new Response($pdf->Output(), 200, array(
        //    'Content-Type' => 'application/pdf'));  
        
    }

    public function rectangle_w_title($pdf, $x, $y, $w, $h, $style, $x1_blank, $x2_blank, $title){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(255);
        $pdf->SetLineWidth(0.5);
        $pdf->Rect($x, $y, $w, $h, $style);
        $pdf->SetLineWidth(0.7);
        $pdf->SetDrawColor(255);
        $pdf->Line($x1_blank, $y, $x2_blank, $y);
        $pdf->SetFont('Arial','',10);
        $x_text = $x1_blank+0.5;
        $y_text = $y+1;
        $pdf->Text($x_text, $y_text, $title);
    }

    public function entete_facture($enseigne, $pdf, $post, $session){
        $image = "../public/img/logo/" . $enseigne->getLogoEnseigne();
        $numCommande = $post['numeroCommande'];
        $dateAchat = $session->get('date_achat');
        $pdf->SetFont('Arial','',12);
        $pdf->Image($image, 10, 10, 33.78);
        $pdf->Text(18,68,utf8_decode("Numéro de commande: "));
        $pdf->Text(18,76,utf8_decode("Acheté le "));
        $pdf->SetXY(64,63);
        $pdf->Cell($pdf->GetStringWidth($numCommande)+6,7,utf8_decode($numCommande),1,0,'L',0);
        $pdf->SetXY(40,71);
        $pdf->Cell(28,7,utf8_decode("$dateAchat"),1,0,'L',0);
    }

    public function cooordonnes_client($pdf, $post){
        $nom = ucfirst($post['client']['nom']);
        $prenom = ucfirst($post['client']['prenom']);
        $telephone = $post['client']['numeroTelephone'];
        /*$nom_rue = $post['client']['adresse']['nom_rue'];
        $complements = $post['client']['adresse']['complements'];
        $ville = ucfirst($post['client']['adresse']['ville']);
        $code_postal = $post['client']['adresse']['code_postal'];
        if($complements == "")
            $adresse = utf8_decode("$prenom $nom\n$nom_rue\n$ville $code_postal");
        else
            $adresse = "$prenom $nom\n$nom_rue\n$complements\n$ville $code_postal";
        $pdf->SetXY(140,35);
        $pdf->SetLeftMargin(143);
        $pdf->Write(5, $adresse);*/
    }

    public function details_table($position, $pdf, $post, $session){
        $categorie = $session->get('categorie');
        $prix = $session->get('prix');
        $pdf->SetDrawColor(183); // Couleur des filets
        $pdf->SetFillColor(255); // Couleur du fond
        $pdf->SetTextColor(198, 7, 1); // Couleur du texte
        //$pdf->SetFont('Arial','',12);
        $pdf->SetXY(25,100);
        $pdf->Cell(40,30,utf8_decode($categorie),0,0,'C',0);
        $pdf->SetX(85,100);
        if($session->get('choix') == 'magasin'){
            $marque = $post['marqueProduit'];
            $pdf->Cell(40,30,utf8_decode($marque),0,0,'C',0);
            $pdf->SetX(145,100);
            $reference = $post['referenceProduit'];
            $pdf->Cell(40,30,utf8_decode($reference),0,0,'C',0);
        }
        else{
            $url = $post['urlProduit'];
            $pdf->Cell(120,30,utf8_decode($url),0,0,'C',0);
            $pdf->SetDrawColor(0,0,255);
            $pdf->Link(85,100, 120, 30,$url);
        }
        $pdf->SetXY(135,150);
        $pdf->Cell(40,30,utf8_decode($prix),0,0,'C',0);
    }

    public function footer($pdf){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(15, 157, 232);
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetFont('Arial','B',16);
        $pdf->SetLeftMargin(0);
        $pdf->SetRightMargin(0);

        $pdf->Rect(0,190, $pdf->GetPageWidth(), 10, 'DF');
        $pdf->SetX(0);
        $pdf->SetY(190);       
        $pdf->Cell($pdf->GetPageWidth(),10,utf8_decode("En cours de recherche..."),1,0,'C',1);
        //$pdf->Text(76,198,utf8_decode("En cours de recherche..."));
        /*$pdf->SetX(0);
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
        $pdf->Cell($pdf->GetPageWidth()/2,8,utf8_decode('Conseils'),1,0,'C',1);*/


        $pdf->Ln(); // Retour à la ligne
    }
}
