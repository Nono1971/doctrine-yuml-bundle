<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

abstract class AbstractOnurbYumlExtensionTest extends TestCase
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

        $this->assertTrue($this->container->getParameter('onurb_yuml.show_fields_description'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.colors'));
        $this->assertSame(array(), $this->container->getParameter('onurb_yuml.notes'));
        $this->assertSame('png', $this->container->getParameter('onurb_yuml.extension'));
        $this->assertSame('plain', $this->container->getParameter('onurb_yuml.style'));
        $this->assertSame('TB', $this->container->getParameter('onurb_yuml.direction'));
        $this->assertSame('normal', $this->container->getParameter('onurb_yuml.scale'));
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

    public function testFalseConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->loadConfiguration($this->container, 'config_false');
        $this->container->compile();

        $this->assertFalse($this->container->getParameter('onurb_yuml.show_fields_description'));
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
        $this->assertSame('png', $this->container->getParameter('onurb_yuml.extension'));
        $this->assertSame('plain', $this->container->getParameter('onurb_yuml.style'));
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

    public function testStyleScruffy()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_style_scruffy');
        $this->container->compile();

        $this->assertSame(
            'scruffy',
            $this->container->getParameter('onurb_yuml.style')
        );
    }

    public function testExtensionSvg()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_extension_svg');
        $this->container->compile();

        $this->assertSame(
            'svg',
            $this->container->getParameter('onurb_yuml.extension')
        );
    }

    public function testDirectionLR()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_direction_LR');
        $this->container->compile();

        $this->assertSame(
            'LR',
            $this->container->getParameter('onurb_yuml.direction')
        );
    }

    public function testScaleTiny()
    {
        $this->container->loadFromExtension($this->extension->getAlias());

        $this->loadConfiguration($this->container, 'config_scale_tiny');
        $this->container->compile();

        $this->assertSame(
            'tiny',
            $this->container->getParameter('onurb_yuml.scale')
        );
    }
}
