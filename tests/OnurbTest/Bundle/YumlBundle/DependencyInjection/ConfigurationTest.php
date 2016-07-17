<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\Configuration;

class ConfigutationTest extends \PHPUnit_Framework_TestCase
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
    public function testGetConfigBuilder()
    {
        $configuration = new Configuration();
        $return = $configuration->getConfigTreeBuilder();
        $this->assertInstanceOf('Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder', $return);
    }
}
