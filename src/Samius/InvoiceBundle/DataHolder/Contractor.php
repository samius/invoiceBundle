<?php

namespace Samius\InvoiceBundle\DataHolder;

class Contractor
{
    const PAYMENT_TYPE_TRANSFER = 'transfer',
        PAYMENT_TYPE_DELIVERY = 'delivery',
        PAYMENT_TYPE_BOTH = 'transfer/delivery';

    /**
     * @var string
     */
    private $company;


    /**
     * @var string
     */
    private $street;


    /**
     * @var string
     */
    private $town;


    /**
     * @var string
     */
    private $zip;


    /**
     * @var string
     */
    private $country;


    /**
     * @var string
     */
    private $ic;


    /**
     * @var string
     */
    private $dic;


    /**
     * @var string
     */
    private $bankName;


    /**
     * @var string
     */
    private $bankNumber;


    /**
     * @var string
     */
    private $paymentType;


    /**
     * Contractor constructor.
     * @param string $company
     * @param string $street
     * @param string $town
     * @param string $zip
     * @param string $country
     * @param string $ic
     * @param string $dic
     * @param $bankName
     * @param $bankNumber
     */
    public function __construct($company, $street, $town, $zip, $country, $ic, $dic, $bankName, $bankNumber, $paymentType)
    {
        $this->company = $company;
        $this->street = $street;
        $this->town = $town;
        $this->zip = $zip;
        $this->country = $country;
        $this->ic = $ic;
        $this->dic = $dic;
        $this->bankName = $bankName;
        $this->bankNumber = $bankNumber;
        $this->setPaymentType($paymentType);
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getIc()
    {
        return $this->ic;
    }

    /**
     * @return string
     */
    public function getDic()
    {
        return $this->dic;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @return string
     */
    public function getBankNumber()
    {
        return $this->bankNumber;
    }

    /**
     * @return string
     */
    public function getPaymentType() 
    {
        return $this->paymentType;
    }

    /**
     * @return bool
     */
    public function isPaymentTypeDelivery() 
    {
        return $this->paymentType == self::PAYMENT_TYPE_DELIVERY;
    }

    /**
     * @return bool
     */
    public function isPaymentTypeTransfer()
    {
        return $this->paymentType == self::PAYMENT_TYPE_TRANSFER;
    }

    /**
     * Default payment type is transfer
     * @param $type
     */
    private function setPaymentType($type)
    {
        if ($type == self::PAYMENT_TYPE_BOTH || $type == self::PAYMENT_TYPE_DELIVERY) {
            $this->paymentType = $type;
        } else {
            $this->paymentType = self::PAYMENT_TYPE_TRANSFER;
        }
    }


}
