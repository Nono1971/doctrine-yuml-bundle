<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigutationTest extends TestCase
{

    /**
     * @covers Onurb\Bundle\YumlBundle\DependencyInjection\Configuration
     */
    public function testIsInstanceOf()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf("Symfony\\Component\\Config\\Definition\\ConfigurationInterface", $configuration);
    }

    /**
     * @covers Onurb\Bundle\YumlBundle\DependencyInjection\Configuration
     */
    public function testGetConfigBuilderInstanceOf()
    {
        $configuration = new Configuration();
        $return = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder', $return);
    }
}
