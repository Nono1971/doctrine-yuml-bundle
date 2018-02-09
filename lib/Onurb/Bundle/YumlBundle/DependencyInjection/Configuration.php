<?php

namespace Onurb\Bundle\YumlBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('onurb_yuml');

        $rootNode
            ->children()
                ->booleanNode('yuml_show_fields_description')
                    ->info('Set true to show fields properties in graph')
                    ->defaultTrue()
                ->end()
                ->arrayNode('yuml_colors')
                    ->prototype('array')
                        ->children()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('yuml_notes')
                    ->prototype('array')
                        ->children()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
