<?php
namespace Samius\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_item")
 */
class InvoiceItem
{
    /**
     * @var Invoice
     * @ORM\ManyToOne(targetEntity="Samius\InvoiceBundle\Entity\Invoice", inversedBy="items")
     */
    private $invoice;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $description;


    /**
     * @ORM\Column(type="integer")
     */
    private $unitPriceWithoutVat;


    /**
     * @ORM\Column(type="integer")
     */
    private $count;


    /**
     * @ORM\Column(type="integer")
     */
    private $vat;


    /**
     * InvoiceItem constructor.
     * @param $description
     * @param $unitPriceWithoutVat
     * @param $count
     * @param $vat
     */
    public function __construct($description, $unitPriceWithoutVat, $count, $vat)
    {
        $this->description = $description;
        $this->unitPriceWithoutVat = $unitPriceWithoutVat * 100;
        $this->count = $count;
        $this->vat = $vat * 100;
    }

    /**
     * @param Invoice $invoice
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setUnitPriceWithoutVat()
    {
        $this->unitPriceWithoutVat * 100;
    }

    /**
     * @return mixed
     */
    public function getUnitPriceWithoutVat()
    {
        return $this->unitPriceWithoutVat / 100;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat / 100;
    }

    /**
     * @return int
     */
    public function getTotalPriceWithoutVat()
    {
        return $this->getCount() * $this->getUnitPriceWithoutVat();
    }
    
    public function getTotalPriceWithVat()
    {
        return $this->getTotalPriceWithoutVat() * $this->getVatRate();
    }
    
    public function getTotalVat()
    {
        return $this->getTotalPriceWithVat() - $this->getTotalPriceWithoutVat();
    }

    /**
     * @return float
     */
    private function getVatRate()
    {
        return (100 + ($this->getVat()))/100;
    }
}