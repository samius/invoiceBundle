<?php
namespace Samius\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_item")
 */
class InvoiceItem
{
    const DESC_ROUND = 'ROUND'; //decription of round item


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
        $this->vat = round($vat * 100);
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

    /**
     * @return float
     */
    public function getUnitPriceWithoutVat()
    {
        return round($this->unitPriceWithoutVat) / 100;
    }

    /**
     * @param mixed $unitPriceWithoutVat
     * @return InvoiceItem
     */
    public function setUnitPriceWithoutVat($unitPriceWithoutVat)
    {
        $this->unitPriceWithoutVat = round($unitPriceWithoutVat * 100);
        return $this;
    }



    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return float
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
        return round($this->count * $this->unitPriceWithoutVat)/100;
    }

    public function getTotalPriceWithVat()
    {
        return round($this->getTotalPriceWithoutVat() * $this->getVatRate()*100) / 100;
    }

    public function getTotalVat()
    {
        return round(100*($this->getTotalPriceWithVat() - $this->getTotalPriceWithoutVat()))/100;
    }

    /**
     * @return float
     */
    private function getVatRate()
    {
        return (100 + ($this->getVat())) / 100;
    }

    /**
     * Creates item with specific description and zero VAT
     * @param $price
     * @return InvoiceItem
     */
    public static function createRoundingItem($price)
    {
        return new self(self::DESC_ROUND, $price, 1, 0);

    }

    /**
     * @return true, if this item is rounding (has specific description and zero VAT)
     */
    public function isRounding()
    {
        return $this->description == self::DESC_ROUND && $this->getVat()== 0;
    }
}