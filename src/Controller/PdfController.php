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
        $token = $session->get('_csrf/demandes');
        //$session->set('path', $post['_token']);
        $session->set('date_achat',  \DateTime::createFromFormat('d-m-Y', $post['dateAchat']));
        
        $array_cat = array('Produits électroniques', 'Maisons et jardins', 'Jeux vidéos et jouets',
            'Santé et beauté', 'Auto et moto', 'Sports et mode');
            //var_dump($array_cat[$session->get('categorie')]);
        $session->set('categorie', $array_cat[$post['categorieProduit']]);

        $nom_enseigne = $post['enseigne'];
                 
        $session->set('choix', $request->request->get('choix'));
        

        $nom_bdd = ucfirst($nom_enseigne);
        $repo = $this->getDoctrine()->getRepository(Enseignes::class);
        $enseigne =  $repo->findOneBy(['nomEnseigne' => $nom_enseigne]);
        $nom = $enseigne->getNomEnseigne();


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

        $pdf->AddPage();
        //$pdf->Image('img/bg.jpg', 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetFont('dejavusans', '', 10);
      
        $this->entete_facture($enseigne, $pdf, $post, $session);      
        $this->cooordonnes_client($pdf, $post);

        $this->rectangle_w_title($pdf, 25,100,40,30,'DF', 30, 46, "Catégorie");
        if($session->get('choix') == 'magasin'){
            $this->rectangle_w_title($pdf, 85,100,40,30,'DF', 90, 103, "Marque" );
            $this->rectangle_w_title($pdf, 145,100,40,30,'DF', 150, 167, "Référence");
        }
        else{
            $this->rectangle_w_title($pdf, 85,100, 120,30,'DF', 90, 98, "URL" );
        }
        
        $this->rectangle_w_title($pdf, 135,150,40,30,'DF', 140, 155, "Prix payé");
        /*$this->rectangle_w_title($pdf, 15,210,45,30,'DF', 17, 57, utf8_decode("Enseigne la moins chère") );
        $this->rectangle_w_title($pdf, 82.5,210,45,30,'DF', 85, 111, "Date du constat" );
        $this->rectangle_w_title($pdf, 150,210,45,30,'DF', 152, 181, utf8_decode("Prix le moins cher"));
        $this->rectangle_w_title($pdf, 35,260,140,30,'DF', 40, 70, utf8_decode("Différence de prix"));*/
        $this->details_table(115, $pdf, $post, $session);
        //$this->footer($pdf, "En cours de recherche...");
        $dir = getcwd();
        $path_pdf = $dir . "\\factures\\" . $token . ".pdf";
        $test1 = $pdf->Output($path_pdf, 'F');
                
        $jpeg = $dir . "/factures/" . $token .".png";
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
        $pdf->SetFont('dejavusans', '', 8);
        $x_text = $x1_blank+0.25;
        $y_text = $y-2;
        $pdf->Text($x_text, $y_text, $title);
    }

    public function entete_facture($enseigne, $pdf, $post, $session){
        $image = "../public/img/logo/" . $enseigne->getLogoEnseigne();
        $numCommande = $post['numeroCommande'];
        $dateAchat = $post['dateAchat'];
        $pdf->SetFont('dejavusans', '', 24);
        $pdf->text(10, 10, $enseigne->getNomEnseigne());
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->Text(18,63,"Numéro de commande: ");
        $pdf->Text(18,71,"Acheté le ");
        $pdf->SetXY(64,63);
        $pdf->Cell($pdf->GetStringWidth($numCommande)+6,7,$numCommande,1,0,'L',0);
        $pdf->SetXY(40,71);
        $pdf->Cell(28,7,$dateAchat,1,0,'L',0);
    }

    public function cooordonnes_client($pdf, $post){
        $nom = ucfirst($post['client']['nom']);
        $prenom = ucfirst($post['client']['prenom']);
        $telephone = $post['client']['numeroTelephone'];
        $nom_rue = $post['client']['adresse']['nom_rue'];
        $ville = ucfirst($post['client']['adresse']['ville']);
        $code_postal = $post['client']['adresse']['code_postal'];
        $adresse = "$prenom $nom\n$nom_rue\n$ville $code_postal";
        $pdf->SetXY(140,35);
        $pdf->SetLeftMargin(143);
        $pdf->Write(5, $adresse);
    }

    public function details_table($position, $pdf, $post, $session){
        $categorie = $session->get('categorie');
        $prix = $post['prixAchat'];
        $pdf->SetDrawColor(183); // Couleur des filets
        $pdf->SetFillColor(255); // Couleur du fond
        $pdf->SetTextColor(198, 7, 1); // Couleur du texte
        //$pdf->SetFont('arial','',12);
        $pdf->SetXY(25,100);
        $pdf->Cell(40,30,$categorie,0,0,'C',0);
        $pdf->SetXY(88,105);
        if($session->get('choix') == 'magasin'){
            $marque = $post['marqueProduit'];
            $pdf->Cell(37,25,$marque,0,0,'C',0);
            $pdf->SetXY(148,100);
            $reference = $post['referenceProduit'];
            $pdf->Cell(37,25,$reference,0,0,'C',0);
        }
        else{
            $url = $post['urlProduit'];
            $pdf->MultiCell(120, 5, $url, 0, 'J', 0, 0, '', '', true, 0, false, true, 40, 'M');
            //$pdf->MultiCell(117,5,utf8_decode($url),0,'C',false);
            $pdf->SetDrawColor(0,0,255);
            $pdf->Link(85,100, 120, 30,$url);
        }
        $pdf->SetXY(135,150);
        $pdf->Cell(40,30,$prix,0,0,'C',0);
    }

    public function footer($pdf, $texte){
        $pdf->SetDrawColor(0); // Couleur des filets
        $pdf->SetFillColor(15, 157, 232);
        $pdf->SetTextColor(0); // Couleur du texte
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->SetLeftMargin(0);
        $pdf->SetRightMargin(0);

        $pdf->Rect(0,190, $pdf->GetPageWidth(), 10, 'DF');
        $pdf->SetX(0);
        $pdf->SetY(190);       
        $pdf->Cell($pdf->GetPageWidth(),10, $texte,1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        $this->rectangle_w_title($pdf, 15,210,45,30,'DF', 17, 54, "Enseigne la moins chère" );
        $this->rectangle_w_title($pdf, 82.5,210,45,30,'DF', 85, 110, "Date du constat" );
        $this->rectangle_w_title($pdf, 150,210,45,30,'DF', 152, 179, "Prix le moins cher");
        $this->rectangle_w_title($pdf, 35,260,140,30,'DF', 40, 67, "Différence de prix");
    }

    public function details_footer($pdf, AlertePrix $alerte, $session){
        $diffPrix = "Vous avez droit à " . $alerte->getDifferencePrix() . "€ de remboursement";
        $pdf->SetXY(15,210);
        $pdf->Cell(45,30,$alerte->getEnseigne(),0,0,'C',0);
        $pdf->SetXY(82.5,210);
        $pdf->Cell(45,30,date_format($alerte->getDate(),"d/m/Y"),0,0,'C',0);
        $pdf->SetXY(150,210);     
        $pdf->Cell(45,30,$alerte->getPrix(),0,0,'C',0);
        $pdf->SetXY(35,260);
        //$pdf->Text(55, 275, $diffPrix);
        $pdf->Cell(140,30,$diffPrix,0,0,'C',0);
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
