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
        $lastNumber = $this->getInvoiceLastNumber($invoice);
        $invoice->setIncrementedNumber($lastNumber);
        $this->em->persist($invoice);
        $this->em->flush();
    }

    /**
     * Returns number of last saved invoice with given format and corresponding year
     * @param Invoice $invoice
     * @return null
     */
    private function getInvoiceLastNumber(Invoice $invoice)
    {
        $res = $this->em->getRepository('InvoiceBundle:Invoice')->createQueryBuilder('i')->select('i.number')
            ->where('i.number LIKE :numberBase')->setParameter('numberBase', $invoice->getNumberBase() . '%')->orderBy('i.number', 'desc')
            ->getQuery()->setMaxResults(1)->getOneOrNullResult();

        if (!$res) {
            return null;
        }

        return $res['number'];
    }
}
