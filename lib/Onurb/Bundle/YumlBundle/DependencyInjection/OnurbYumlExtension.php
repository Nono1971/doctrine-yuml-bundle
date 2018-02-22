<?php

namespace Onurb\Bundle\YumlBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OnurbYumlExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'onurb_yuml.show_fields_description',
            $configs['yuml_show_fields_description']
        );

        $container->setParameter(
            'onurb_yuml.colors',
            $configs['yuml_colors']
        );

        $container->setParameter(
            'onurb_yuml.notes',
            $configs['yuml_notes']
        );

        $container->setParameter(
            'onurb_yuml.extension',
            $configs['yuml_extension']
        );

        $container->setParameter(
            'onurb_yuml.style',
            $configs['yuml_style']
        );

        $container->setParameter(
            'onurb_yuml.direction',
            $configs['yuml_direction']
        );

        $container->setParameter(
            'onurb_yuml.scale',
            $configs['yuml_scale']
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
