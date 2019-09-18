<?php
namespace App\Services;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader;
use App\Entity\Demandes;
/**
 * Class Facture
 */
class Facture
{
    public function depot($num){  
        $repo = $this->getDoctrine()->getRepository(Demandes::class);
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
        return $pdf
    }
}