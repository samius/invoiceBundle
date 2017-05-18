InvoiceBundle
-------------
Installation:
- composer require samius/invoice-bundle
- Add to AppKernel.php
- Add to your config.yml:
```
invoice:
    contractor:
        company: "BetaPrint s.r.o."
        street: "Ulice"
        town: "Mesto"
        zip: "123 45"
        ic: "123456"
        dic: "CZ123456"
        country: "Czech Republic"
        vatpayer: true
    bank:
        name: "FIO"
        number: "1234567489/2010"
        payment_type: "transfer/delivery"

```


- Create converter from your orders
- Invoice lines have title and subtitle (subtitle is displayed smaller). Line format is title@@subtitle.

