<?php
namespace Samius\InvoiceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class InvoiceExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('invoice_contractor_company', $config['contractor']['company']);
        $container->setParameter('invoice_contractor_street', $config['contractor']['street']);
        $container->setParameter('invoice_contractor_town', $config['contractor']['town']);
        $container->setParameter('invoice_contractor_zip', $config['contractor']['zip']);
        $container->setParameter('invoice_contractor_ic', $config['contractor']['ic']);
        $container->setParameter('invoice_contractor_dic', $config['contractor']['dic']);
        $container->setParameter('invoice_contractor_country', $config['contractor']['country']);

        $container->setParameter('invoice_bank_name', $config['bank']['name']);
        $container->setParameter('invoice_bank_number', $config['bank']['number']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}