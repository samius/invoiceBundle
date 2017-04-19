<?php
namespace Samius\InvoiceBundle\Converter;

use Samius\InvoiceBundle\DataHolder\Contractor;
use Samius\InvoiceBundle\Entity\Invoice;
use Twig_Environment;

class PdfConverter
{
    /**
     * @var \TCPDF
     */
    private $tcpdf;

    /**
     * @var Twig_Environment
     */
    private $twig;


    /**
     * @var Contractor
     */
    private $contractor;


    /**
     * @param \TCPDF $tcpdf
     * @param Twig_Environment $twig
     * @param Contractor $contractor
     */
    public function __construct(\TCPDF $tcpdf, Twig_Environment $twig, Contractor $contractor)
    {
        $this->tcpdf = $tcpdf;
        $this->twig = $twig;
        $this->contractor = $contractor;
    }

    public function convertInvoiceToPdf(Invoice $invoice)
    {
        $html = $this->twig->render('@Invoice/invoice.html.twig', ['invoice' => $invoice, 'contractor'=>$this->contractor]);

        // create new PDF document
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output($invoice->getNumber(), 'S');
    }
}
