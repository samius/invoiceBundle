<?php
namespace Samius\InvoiceBundle\Service;

use Doctrine\ORM\EntityManager;
use Samius\InvoiceBundle\Entity\Invoice;

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

    /**
     * @param Invoice $invoice
     */
    public function saveInvoice(Invoice $invoice)
    {
        $number = $this->getInvoiceLastNumber($invoice);
    }

    /**
     * Returns number of last saved invoice with given format and corresponding year
     * @param Invoice $invoice
     */
    private function getInvoiceLastNumber(Invoice $invoice)
    {
        $res = $this->em->getRepository('Invoice')->createQueryBuilder('i')->where('number LIKE :numberBase')->setParameter('numberBase', $invoice->getNumberBase() . '%')
            ->getQuery()->getFirstResult();

        dump($res); die();


    }
    
    private function getNumberFromFormat()
    {
        
    }
}
