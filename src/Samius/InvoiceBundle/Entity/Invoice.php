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
     * @ORM\Column(type="string")
     */
    private $invoiceName;


    /**
     * @ORM\Column(type="string")
     */private $invoiceCompany;


    /**
     * @ORM\Column(type="string")
     */private $invoiceIc;


    /**
     * @ORM\Column(type="string")
     */private $invoiceDic;


    /**
     * @ORM\Column(type="string")
     */private $invoiceStreet;


    /**
     * @ORM\Column(type="string")
     */private $invoiceTown;


    /**
     * @ORM\Column(type="string")
     */private $invoiceZip;


    /**
     * @ORM\Column(type="string")
     */private $invoiceCountry;


    /**
     * @ORM\Column(type="string")
     */
    private $deliveryName;


    /**
     * @ORM\Column(type="string")
     */private $deliveryCompany;


    /**
     * @ORM\Column(type="string")
     */private $deliveryStreet;


    /**
     * @ORM\Column(type="string")
     */private $deliveryTown;


    /**
     * @ORM\Column(type="string")
     */private $deliveryZip;


    /**
     * @ORM\Column(type="string")
     */private $deliveryCountry;


    /**
     * @var ArrayCollection
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
        if (!preg_match('/^\{(YY){1,2}}.*#\{\d\}$/', $numberFormat)){
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
        $sequence = str_replace($base, '', $number);
        return (int)$sequence;
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
    public function setRecipient($name, $company, $street, $town, $zip, $country, $ic = null, $dic=null)
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
     * @return string
     */
    public function getNumberFormat()
    {
        return $this->numberFormat;
    }
}
