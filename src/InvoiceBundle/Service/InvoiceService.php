<?php
namespace Samius\InvoiceBundle\Service;

use Doctrine\ORM\EntityManager;

class InvoiceService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function saveInvoice($invoice)
    {
        $number = $this->getInvoiceNewNumber($invoice->getNumberFormat);
    }
    
    private function getInvoiceNewNumber()
    {
        $this->em->getRepository('Invoice')
        //get last number
        //increment number
    }
}
