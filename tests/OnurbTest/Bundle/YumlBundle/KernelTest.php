<?php
declare(strict_types=1);

namespace OnurbTest\Bundle\YumlBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Onurb\Bundle\YumlBundle\OnurbYumlBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @coversNothing
 */
class KernelTest extends KernelTestCase
{
    protected static function createKernel(array $options = [])
    {
        return new class('test', true) extends Kernel {

            public function getCacheDir()
            {
                return sys_get_temp_dir() . '/cache/' . spl_object_hash($this);
            }

            public function registerBundles()
            {
                return [
                    new FrameworkBundle(),
                    new DoctrineBundle(),
                    new OnurbYumlBundle(),
                ];
            }

            public function registerContainerConfiguration(LoaderInterface $loader)
            {
                $loader->load(static function (ContainerBuilder $container) {
                    $container->loadFromExtension('framework', [
                        'secret' => 'foo',
                    ]);
                    $container->loadFromExtension('doctrine', [
                        'dbal' => [],
                        'orm' => [],
                    ]);

                    $container->setAlias('test.onurb_yuml.client', 'onurb_yuml.client');
                });
            }
        };
    }

    public function testKernelCompiles(): void
    {
        if (version_compare(Kernel::VERSION, '5.0') === -1) {
            $this->markTestSkipped(
                'Kernel test requires at least Symfony 5.0 due to issues with test container compilation'
            );
        }

        self::bootKernel();

        $client = self::$container->get('onurb_yuml.client');

        $this->assertNotNull($client);
    }
}