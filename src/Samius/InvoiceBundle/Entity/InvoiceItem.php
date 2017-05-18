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
    const SUBTITLE_DELIMITER = '@@';//item description can be like title@@subtitle


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
        $this->vat = floor($vat * 100);
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

    public function getTitle()
    {
        $parts = explode(self::SUBTITLE_DELIMITER, $this->description);
        return $parts[0];
    }
    
    public function getSubtitle()
    {
        $parts = explode(self::SUBTITLE_DELIMITER, $this->description);
        if (isset($parts[1])) {
            return $parts[1];
        }
        return '';
    }

    /**
     * @return float
     */
    public function getUnitPriceWithoutVat()
    {
        return floor($this->unitPriceWithoutVat) / 100;
    }

    /**
     * @param mixed $unitPriceWithoutVat
     * @return InvoiceItem
     */
    public function setUnitPriceWithoutVat($unitPriceWithoutVat)
    {
        $this->unitPriceWithoutVat = floor($unitPriceWithoutVat * 100);
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
        return floor($this->count * $this->unitPriceWithoutVat)/100;
    }

    public function getTotalPriceWithVat()
    {
        return floor($this->getTotalPriceWithoutVat() * $this->getVatRate()*100) / 100;
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