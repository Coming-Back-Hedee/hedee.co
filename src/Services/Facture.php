<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;

use App\Entity\AlertePrix;
use App\Entity\Demandes;
use App\Repository\DemandesRepository;

/**
 * Class Facture
 */
class Facture
{
    public function depot(DemandesRepository $repo, $num){  
        //$repo = $this->getDoctrine()->getRepository(Demandes::class);
        $demande = $repo->find($num);

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
        $dir = getcwd();
        $bgImg = $dir . "/img/facture/bg.png";
        $pdf->Image($bgImg, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->SetFont('dejavusans', '', 10);
      
        $this->entete_facture($pdf, $demande);      
        $this->cooordonnes_client($pdf, $demande);
        $this->details_table(80, $pdf, $demande);

        return $pdf;
    }

    public function alerte(DemandesRepository $repo, $num, AlertePrix $alerte){ 
        $pdf = $this->depot($num, $repo); 
        $this->footer($pdf); 
        $this->details_footer($pdf, $alerte);
        return $pdf;

    }

    public function entete_facture($pdf, $demande){
        $numCommande = $demande->getNumeroCommande();
        $pdf->SetFont('dejavusans', 'B', 24);
        $pdf->text(PDF_MARGIN_LEFT, 35, $demande->getEnseigne());
        //$pdf->SetXY(40,71);
        //$pdf->Cell(28,7,$dateAchat,1,0,'L',0);
    }

    public function cooordonnes_client($pdf, $demande){
        $nom = ucfirst($demande->getClient()->getNom());
        $prenom = ucfirst($demande->getClient()->getPrenom());
        $telephone = $demande->getClient()->getNumeroTelephone();
        $nom_rue = $demande->getClient()->getAdresse()->getNomRue();
        $ville = ucfirst($demande->getClient()->getAdresse()->getVille());
        $code_postal = $demande->getClient()->getAdresse()->getCodePostal();
        $adresse = "$nom_rue\n$ville $code_postal\n0$telephone";
        $pdf->SetXY(140,35);
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Write(5, "$prenom $nom");
        $pdf->SetXY(140,40);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetLeftMargin(143);
        $pdf->Write(5, $adresse);
    }

    public function details_table($position, $pdf, $demande){
        $categorie = $demande->getCategorieProduit();
        $prix = $demande->getPrixAchat();
        $details = "Catégorie : ";
        $points = "............................................................................................";
        $pdf->SetXY(PDF_MARGIN_LEFT, $position + 2);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetLeftMargin(PDF_MARGIN_LEFT);
        //
        //$pdf->Cell(50,$position-2, "test 1 2 1 2",0,0,'C',0);
        if($demande->getUrlProduit() == null){
            $details .= "\nEnseigne : ";
            $details .= "\nRéference : ";
            $details .= "\nMontant de l'achat : ";
            $pdf->Write(10, $details);
            $marque = $demande->getMarqueProduit();
            $reference = $demande->getReferenceProduit();
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
            $url = $demande->getUrlProduit();
            
            $nbLignes = ($pdf->GetStringWidth($url) / ($pdf->GetPageWidth() - PDF_MARGIN_RIGHT - PDF_MARGIN_LEFT - 70));
            $details .= "\nMontant de l'achat : ";
            $details .= "\nURL : ";
            $points .= "\n............................................................................................";          
            $pdf->Write(10, $details);
            $pdf->SetXY(PDF_MARGIN_LEFT+50, $position+4);
            $pdf->SetLeftMargin(PDF_MARGIN_LEFT+50);
            $pdf->Write(10, $points);
            $pdf->Text(PDF_MARGIN_LEFT+50, $position + 14, "$prix €");
            
            $pdf->SetXY(PDF_MARGIN_LEFT+50,$position + 24);
            $pdf->MultiCell(120, 5, $url, 1, 'J', 1, 0, '', '', true, 0, false, true, 40, 'T');
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

    public function details_footer($pdf, AlertePrix $alerte){
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
}