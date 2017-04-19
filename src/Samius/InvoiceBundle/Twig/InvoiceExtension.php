<?php
namespace Samius\InvoiceBundle\Twig;

use AppBundle\Entity\Course;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class InvoiceExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('invoiceprice', array($this, 'priceFilter')),

        );
    }

    public function priceFilter($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $number =  $number;
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);

        return $price;
    }

    public function getName()
    {
        return 'invoice_extension';
    }
}