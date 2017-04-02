<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

abstract class AbstractOnurbYumlExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OnurbYumlExtension
     */
    private $extension;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->extension = new OnurbYumlExtension();
        $this->container = new ContainerBuilder();

        $this->container->registerExtension($this->extension);

        $loader = new Loader\YamlFileLoader($this->container, new FileLocator(__DIR__ . '/Fixtures/'));
        $loader->load('fake_doctrine_service.yml');
    }

    abstract protected function loadConfiguration(ContainerBuilder $container, $resource);

    public function testDefaultConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertFalse($this->container->has('onurb_yuml'));
    }

    public function testDefaultParams()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertFalse($this->container->getParameter('onurb_yuml.show_fields_description'));

        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.colors'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.notes'));
    }

    public function testTrueConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->loadConfiguration($this->container, 'config_true');
        $this->container->compile();

        $this->assertTrue($this->container->getParameter('onurb_yuml.show_fields_description'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.colors'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.notes'));
    }

    public function testPartialConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_partial');
        $this->container->compile();

        $this->assertTrue($this->container->getParameter('onurb_yuml.show_fields_description'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.colors'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.notes'));
    }

    public function testArrayColorsConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_colors');
        $this->container->compile();

        $this->assertSame(
            array(
                'My\Class' => 'green',
                'My\OtherClass' => 'blue'
            ),
            $this->container->getParameter('onurb_yuml.colors')
        );
    }
    public function testArrayNotesConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_notes');
        $this->container->compile();

        $this->assertSame(
            array(
                'My\Class' => 'My note',
                'My\OtherClass' => 'My other note'
            ),
            $this->container->getParameter('onurb_yuml.notes')
        );
    }
}
