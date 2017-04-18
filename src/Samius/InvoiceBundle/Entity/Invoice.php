<?php
namespace Samius\InvoiceBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Samius\InvoiceBundle\Exception\BadNumberFormatException;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice")
 */
class Invoice
{

    const CURRENCY_CZK = 'CZK',
          CURRENCY_EUR = 'EUR',
          CURRENCY_USD = 'USD';

    /**
     * @ORM\Column(type="string")
     */
    private $numberFormat;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $number;


    /**
     * @ORM\Column(type="date")
     */
    private $dateCreated;


    /**
     * @ORM\Column(type="date")
     */
    private $dueDate;


    /**
     * @ORM\Column(type="string")
     */
    private $currency;


    /**
     * @ORM\Column(type="string")
     */
    private $invoiceName;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $invoiceCompany;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $invoiceIc;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $invoiceDic;


    /**
     * @ORM\Column(type="string")
     */
    private $invoiceStreet;


    /**
     * @ORM\Column(type="string")
     */
    private $invoiceTown;


    /**
     * @ORM\Column(type="string")
     */
    private $invoiceZip;


    /**
     * @ORM\Column(type="string")
     */
    private $invoiceCountry;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryName;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $deliveryCompany;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryStreet;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryTown;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryZip;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryCountry;


    /**
     * @var InvoiceItem[]
     * @ORM\OneToMany(targetEntity="Samius\InvoiceBundle\Entity\InvoiceItem", mappedBy="invoice", cascade={"persist", "remove"})
     */
    private $items;


    /**
     * Invoice constructor.
     * @param \DateTime $dateCreated
     * @param string $numberFormat
     */
    public function __construct(\DateTime $dateCreated, $numberFormat = '{YY}#{6}')
    {
        $this->checkNumberFormat($numberFormat);
        $this->dateCreated = $dateCreated;
        $this->numberFormat = $numberFormat;
        $this->items = new ArrayCollection();
        $this->getNumberBase();
    }

    private function checkNumberFormat($numberFormat)
    {
        if (!preg_match('/^\{(YY){1,2}}.*#\{\d\}$/', $numberFormat)) {
            throw new BadNumberFormatException();
        }
    }

    /**
     * Returns sequence part of invoice number
     * i.e. from 2017xx000001 returns "1"
     * @param string $number
     * @return int
     */
    private function getSequenceFromNumber($number)
    {
        $base = $this->getNumberBase();
        $sequence = preg_replace('/^' . $base . '/', '', $number);
        return intval($sequence);
    }

    /**
     * Returns number of digits of sequence part of invoice number.
     * i.e. numbeFormat = {YY}AA#{9}, sequence part is 9 digits long.
     */
    private function getSequenceLength()
    {
        preg_match('/.*\#{(\d)\}$/', $this->numberFormat, $matches);
        return $matches[1];
    }


    /**
     * According to number format returns begining of string expcept the number..
     * i.e. from {YYYY}xxx#{5} returns 2017xxx
     */
    public function getNumberBase()
    {
        $format = $this->numberFormat;
        $format = str_replace('{YYYY}', $this->dateCreated->format('Y'), $format);
        $format = str_replace('{YY}', $this->dateCreated->format('y'), $format);

        $format = preg_replace('/#\{\d\}$/', '', $format);
        return $format;
    }

    /**
     * Sets incremented number according to numberFormat and lastnumber
     * @param string $lastNumber
     * @return string
     */
    public function setIncrementedNumber($lastNumber)
    {
        $number = $this->getNumberBase();

        if ($lastNumber == '') {
            $sequence = 1;
        } else {
            $lastSequence = $this->getSequenceFromNumber($lastNumber);
            $sequence = $lastSequence + 1;
        }

        $number .= str_pad($sequence, $this->getSequenceLength(), '0', STR_PAD_LEFT);

        $this->number = $number;
        return $number;
    }

    public function addItem(InvoiceItem $item)
    {
        $this->items->add($item);
        $item->setInvoice($this);
    }

    /**
     * @param $name
     * @param $company
     * @param $street
     * @param $town
     * @param $zip
     * @param $country
     * @param null $ic
     * @param null $dic
     */
    public function setRecipient($name, $company, $street, $town, $zip, $country, $ic = null, $dic = null)
    {
        $this->invoiceName = $name;
        $this->invoiceCompany = $company;
        $this->invoiceStreet = $street;
        $this->invoiceTown = $town;
        $this->invoiceZip = $zip;
        $this->invoiceCountry = $country;
        $this->ic = $ic;
        $this->dic = $dic;
    }

    /**
     * @param $name
     * @param $company
     * @param $street
     * @param $town
     * @param $zip
     * @param $country
     */
    public function setRecipientAddress($name, $company, $street, $town, $zip, $country)
    {
        $this->deliveryName = $name;
        $this->deliveryCompany = $company;
        $this->deliveryStreet = $street;
        $this->deliveryTown = $town;
        $this->deliveryZip = $zip;
        $this->deliveryCountry = $country;
    }

    /**
     * Rounds price with VAT to whole CZK.
     * Adds one round item
     * If invoice is in EUR (or is already rounded), does nothing
     *
     * @return Invoice
     */
    public function addRoundItem()
    {
        if ($this->isRounded()) {
            return $this;
        }

        $roundItem = $this->getRoundItem();
        if (!$roundItem) {
            $roundItem = InvoiceItem::createRoundingItem($this->getRoundDiffPrice());
            $this->items->add($roundItem);
            $roundItem->setInvoice($this);
        } else {
            $roundItem->setUnitPriceWithoutVat(0);//diffPrice should not be involved by rounding price
            $roundItem->setUnitPriceWithoutVat($this->getRoundDiffPrice());
        }

        return $this;
    }

    /**
     * @return InvoiceItem | false
     */
    public function getRoundItem()
    {
        foreach ($this->items as $item) {
            if ($item->isRounding()) {
                return $item;
            }
        }
        return false;
    }

    /**
     * @return bool whether the invoice (in CZK) is rounded to whole CZK
     */
    public function isRounded()
    {
        if ($this->currency != self::CURRENCY_CZK) {
            return true;
        }

        return $this->getRoundDiffPrice() == 0;
    }

    /**
     * @return int price in hellers, that rounding item should have
     */
    private function getRoundDiffPrice()
    {
        $targetPrice = round($this->getTotalPriceWithVat());
        $diff = $targetPrice - $this->getTotalPriceWithVat();

        return $diff;
    }


    /**
     * @return float
     */
    public function getTotalPriceWithoutVat()
    {
        $price = 0;
        foreach ($this->getItems() as $item) {
            $price += $item->getTotalPriceWithoutVat();
        }
        return $price;
    }

    /**
     * @return float
     */
    public function getTotalPriceWithVat()
    {
        $price = 0;
        foreach ($this->getItems() as $item) {
            $price += round($item->getTotalPriceWithVat()*100);
        }

        return $price / 100;
    }

    /**
     * @return float
     */
    public function getTotalVat()
    {
        $vat = 0;
        foreach ($this->getItems() as $item) {
            $vat += $item->getTotalVat();
        }
        return $vat;
    }

    /**
     * @return InvoiceItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getInvoiceName()
    {
        return $this->invoiceName;
    }

    /**
     * @return mixed
     */
    public function getInvoiceCompany()
    {
        return $this->invoiceCompany;
    }

    /**
     * @return mixed
     */
    public function getInvoiceIc()
    {
        return $this->invoiceIc;
    }

    /**
     * @return mixed
     */
    public function getInvoiceDic()
    {
        return $this->invoiceDic;
    }

    /**
     * @return mixed
     */
    public function getInvoiceStreet()
    {
        return $this->invoiceStreet;
    }

    /**
     * @return mixed
     */
    public function getInvoiceTown()
    {
        return $this->invoiceTown;
    }

    /**
     * @return mixed
     */
    public function getInvoiceZip()
    {
        return $this->invoiceZip;
    }

    /**
     * @return mixed
     */
    public function getInvoiceCountry()
    {
        return $this->invoiceCountry;
    }

    /**
     * @param \DateTime $dueDate
     */
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Invoice
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }


}
