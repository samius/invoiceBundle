<?php
namespace Samius\InvoiceBundle\Entity;

class InvoiceItem
{
    /**
     * @var Invoice
     */
    private $invoice;
    private $id;
    private $description;
    private $unitPriceWithoutVat;
    private $count;
    private $vat;
    

    public function __construct($description, $unitPriceWithoutVat, $count, $vat)
    {
        $this->description = $description;
        $this->unitPriceWithoutVat = $unitPriceWithoutVat * 100;
        $this->count = $count;
        $this->vat = $vat;
    }

    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
    
    
}