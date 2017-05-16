<?php

namespace Samius\InvoiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('invoices');

        $rootNode
            ->children()
                ->arrayNode('contractor')
                    ->children()
                        ->scalarNode('company')->defaultFalse()->end()
                        ->scalarNode('street')->defaultFalse()->end()
                        ->scalarNode('town')->defaultFalse()->end()
                        ->scalarNode('zip')->defaultFalse()->end()
                        ->scalarNode('country')->defaultFalse()->end()
                        ->scalarNode('ic')->defaultFalse()->end()
                        ->scalarNode('dic')->defaultFalse()->end()
                        ->scalarNode('vatpayer')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('bank')
                    ->children()
                        ->scalarNode('name')->defaultFalse()->end()
                        ->scalarNode('number')->defaultFalse()->end()
                        ->scalarNode('payment_type')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();



        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
