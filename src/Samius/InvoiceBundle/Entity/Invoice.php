<?php
namespace Samius\InvoiceBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class Invoice
{
    private $numberFormat;

    private $id;


    private $number;

    private $invoiceName;
    private $invoiceCompany;
    private $invoiceIc;
    private $invoiceDic;
    private $invoiceStreet;
    private $invoiceTown;
    private $invoiceZip;
    private $invoiceCountry;

    private $deliveryName;
    private $deliveryCompany;
    private $deliveryStreet;
    private $deliveryTown;
    private $deliveryZip;
    private $deliveryCountry;


    /**
     * @var ArrayCollection
     */
    private $items;


    /**
     * Invoice constructor.
     * @param string $numberFormat
     */
    public function __construct($numberFormat = '{YY}#{6}')
    {
        $this->numberFormat = $numberFormat;
        $this->items = new ArrayCollection();
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
