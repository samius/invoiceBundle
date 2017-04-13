<?php
namespace Samius\InvoiceBundle\Exception;

class BadNumberFormatException extends InvoiceException
{
    protected $message = 'Number format must contain "{YY}" or "{YYYY}" as year, and must end with #{\d} as number length (\d:1-9)';
}
