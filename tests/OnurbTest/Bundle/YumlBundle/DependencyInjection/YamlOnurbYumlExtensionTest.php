<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class YamlOnurbYumlExtensionTest extends AbstractOnurbYumlExtensionTest
{

    protected function loadConfiguration(ContainerBuilder $container, $resource)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Fixtures/Yaml/'));
        $loader->load($resource . '.yml');
    }
}
