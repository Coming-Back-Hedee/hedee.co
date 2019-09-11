<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Console\Output\OutputInterface;

use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;

use App\Entity\Enseignes;
use App\Entity\AlertePrix;

class PdfController extends AbstractController
{
    /**
     * @Route("/facture", name="facture")
     */
    public function index(Request $request)
    {
        $post = $request->request->get('demandes');
        $session = $request->getSession();        

        $nom_enseigne = $session->get('enseigne');;
                 
        $session->set('choix', $request->request->get('choix'));
        
        $nom_bdd = ucfirst($nom_enseigne);
        $repo = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repo->findOneBy(['nomEnseigne' => $nom_enseigne]);
        $dir = getcwd();
        $bgImg = $dir . "\\img\\facture\\bg.png";


        //$pdf = new \FPDF();
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(FALSE);
        $pdf->SetTextColor(40, 0, 220);
        $pdf->AddPage();
        $pdf->Image($bgImg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetFont('dejavusans', '', 10);
      
        $this->entete_facture($enseigne, $pdf, $post, $session);      
        $this->cooordonnes_client($pdf, $post);
        $this->details_table(80, $pdf, $post, $session);
        /*$this->rectangle_w_title($pdf, 25,100,40,30,'DF', 30, 46, "Catégorie");
        if($session->get('choix') == 'magasin'){
            $this->rectangle_w_title($pdf, 85,100,40,30,'DF', 90, 103, "Marque" );
            $this->rectangle_w_title($pdf, 145,100,40,30,'DF', 150, 167, "Référence");
        }
        else{
            $this->rectangle_w_title($pdf, 85,100, 120,30,'DF', 90, 98, "URL" );
        }
        
        $this->rectangle_w_title($pdf, 135,150,40,30,'DF', 140, 155, "Prix payé");*/
        
       
        $path_pdf = $dir . "\\factures\\" . $session->get('path') . ".pdf";
        $test1 = $pdf->Output($path_pdf, 'F');
                
        $jpeg = $dir . "/factures/" . $session->get('path') .".png";
        /*exec("magick convert $path_pdf -colorspace RGB -density 300 -quality 85 $jpeg");
        

        $data = ['path' => $jpeg];*/
        exec("magick convert $path_pdf -colorspace RGB -density 300 -trim  -quality 100 $jpeg");
        
        //return new JsonResponse(true);

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));  
        
    }

    public function rectangle_w_title($pdf, $x, $y, $w, $h, $style, $x1_blank, $x2_blank, $title){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(255);
        $pdf->SetLineWidth(0.5);
        $pdf->Rect($x, $y, $w, $h, $style);
        $pdf->SetLineWidth(0.7);
        $pdf->SetDrawColor(40, 0, 220);
        $pdf->Line($x1_blank, $y, $x2_blank, $y);
        $pdf->SetFont('dejavusans', '', 8);
        $x_text = $x1_blank+0.25;
        $y_text = $y-2;
        $pdf->Text($x_text, $y_text, $title);
    }

    public function entete_facture($enseigne, $pdf, $post, $session){
        $numCommande = $post['numeroCommande'];
        $dateAchat = $session->get('date_achat');
        $pdf->SetFont('dejavusans', 'B', 24);
        $pdf->text(PDF_MARGIN_LEFT, 35, $enseigne->getNomEnseigne());
        //$pdf->SetXY(40,71);
        //$pdf->Cell(28,7,$dateAchat,1,0,'L',0);
    }

    public function cooordonnes_client($pdf, $post){
        $nom = ucfirst($post['client']['nom']);
        $prenom = ucfirst($post['client']['prenom']);
        $telephone = $post['client']['numeroTelephone'];
        $nom_rue = $post['client']['adresse']['nomRue'];
        $ville = ucfirst($post['client']['adresse']['ville']);
        $code_postal = $post['client']['adresse']['codePostal'];
        $adresse = "$nom_rue\n$ville $code_postal\n0$telephone";
        $pdf->SetXY(140,35);
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Write(5, "$prenom $nom");
        $pdf->SetXY(140,40);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetLeftMargin(143);
        $pdf->Write(5, $adresse);
    }

    public function details_table($position, $pdf, $post, $session){
        $categorie = $session->get('categorie');
        $prix = $session->get('prix');
        $details = "Catégorie : ";
        $points = "............................................................................................";
        $pdf->SetXY(PDF_MARGIN_LEFT, $position + 2);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetLeftMargin(PDF_MARGIN_LEFT);
        //
        //$pdf->Cell(50,$position-2, "test 1 2 1 2",0,0,'C',0);
        if($session->get('choix') == 'magasin'){
            $details .= "\nEnseigne : ";
            $details .= "\nRéference : ";
            $details .= "\nMontant de l'achat : ";
            $pdf->Write(10, $details);
            $marque = $post['marqueProduit'];
            $reference = $post['referenceProduit'];
            $points .= "\n...........................................................................................";
            $points .= "\n...........................................................................................";
            $points .= "\n...........................................................................................";
            $pdf->SetXY(PDF_MARGIN_LEFT+50, $position+4);
            $pdf->SetLeftMargin(PDF_MARGIN_LEFT+50);
            $pdf->Write(10, $points);
            $pdf->Text(PDF_MARGIN_LEFT+50, $position + 14, $marque);
            $pdf->Text(PDF_MARGIN_LEFT+50, $position + 24, $reference);
            $pdf->Text(PDF_MARGIN_LEFT+50, $position + 34, "$prix €");
            

            /*$pdf->Cell(37,25,$marque,0,0,'C',0);
            $pdf->SetXY(148,100);
            
            $pdf->Cell(37,25,$reference,0,0,'C',0);*/
        }
        else{
            $url = $post['urlProduit'];
            
            $nbLignes = ceil($pdf->GetStringWidth($url) / ($pdf->GetPageWidth() - 2 * PDF_MARGIN_LEFT - 50));
            $details .= "\nURL : ";
            
            for($i = 0; $i < $nbLignes; $i++){
                $points .= "\n";
                $details .= "\n";
            }
            $points .= "\n............................................................................................";
            $details .= "Montant de l'achat : ";
            $pdf->Write(10, $details);
            $pdf->SetXY(PDF_MARGIN_LEFT+50, $position+4);
            $pdf->SetLeftMargin(PDF_MARGIN_LEFT+50);
            $pdf->Write(10, $points);
            
            $pdf->SetXY(PDF_MARGIN_LEFT+50, $position+4);
            //$pdf->Write(10, "$url ");
            
            $pdf->MultiCell(120, 5, $url, 1, 'J', 0, 0, '', '', true, 0, false, true, 40, 'M');
            //$pdf->MultiCell(117,5,utf8_decode($url),0,'C',false);
            //$pdf->SetDrawColor(0,0,255);
            $pdf->Link(PDF_MARGIN_LEFT+50, $position+15, 120, $nbLignes*5 ,$url);
            $pdf->Text(PDF_MARGIN_LEFT+50, $position+($nbLignes+5)*5 , "$prix €");
        }
        $pdf->Text(PDF_MARGIN_LEFT+50, $position + 4, $categorie);
         
    }

    public function footer($pdf){
        $pdf->SetFillColor(255);
        $pdf->Rect(0, 155, $pdf->GetPageWidth(), $pdf->GetPageHeight(), 'F');
        $pdf->SetTextColor(40, 0, 220);
        $pdf->SetFillColor(40, 0, 220);
        $texte = "Hedee a trouvé moins cher !";
        $mid_x = $pdf->GetPageWidth()/2; // the middle of the "PDF screen", fixed by now.
        $pdf->SetDrawColor(40, 0, 220); // Couleur des filets
        $pdf->SetTextColor(40, 0, 220);
        $pdf->SetFont('dejavusans', 'B', 12);
        
        $pdf->Text($mid_x - ($pdf->GetStringWidth($texte) / 2), 140, $texte);
        $pdf->Rect(15,210,45,30,'D');
        $pdf->Rect(82.5,210,45,30,'D');
        $pdf->Rect(150,210,45,30,'D');
    }

    public function details_footer($pdf, AlertePrix $alerte, $session){
        $mid_x = $pdf->GetPageWidth()/2; // the middle of the "PDF screen", fixed by now.
        $pdf->SetFont('dejavusans', '', 24);
        $diffPrix = $alerte->getDifferencePrix() . "€";
        $prix = $alerte->getPrix() . "€";
        $enseigne = $alerte->getEnseigne();
        $date = date_format($alerte->getDate(),'d/m/Y');
        $pdf->Text($mid_x - ($pdf->GetStringWidth("Vous pouvez gagner") / 2), 155, "Vous pouvez gagner");      
        $pdf->Text($mid_x - ($pdf->GetStringWidth($diffPrix) / 2), 165,$diffPrix);
        $pdf->SetFont('dejavusans', 'B', 9);
        $pdf->Text(37.5 - ($pdf->GetStringWidth("Magasin:") / 2), 200, "Magasin:");
        $pdf->Text(105 - ($pdf->GetStringWidth("Date du constat:") / 2), 200, "Date du constat:");
        $pdf->Text(172.5 - ($pdf->GetStringWidth("Prix le moins cher") / 2), 200, "Prix le moins cher"); 

        $pdf->SetXY(15,210);
        
        $pdf->Cell(45,30, $enseigne,0,0,'C',0);
        $pdf->SetXY(82.5,210);
        
        $pdf->Cell(45,30, $date,0,0,'C',0);
        $pdf->SetXY(150,210);
            
        $pdf->Cell(45,30, $prix, 0, 0,'C',0);
    }

    public function recup_pdf($pdf, $path){
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(0);
        $pdf->SetAutoPageBreak(FALSE);
        $pdf->setFontSubsetting(TRUE);           
        $pdf->setSourceFile($path);
        $templateId = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

        //return $pdf;
    }
}
