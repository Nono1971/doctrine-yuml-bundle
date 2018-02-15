<?php
namespace OnurbTest\Bundle\YumlBundle\Command;

use Onurb\Bundle\YumlBundle\Command\YumlCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Tester\CommandTester;

class YumlCommandTest extends TestCase
{
    const YUML_LINK = 'https://yuml.me/15a98c92.png';

    /**
     * @var Application
     */
    private $application;

    /**
     * @var YumlCommand
     */
    private $command;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var CommandTester
     */
    private $commandTester;

    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->add(new YumlCommand());
        $this->command = $this->application->find('yuml:mappings');

        $yumlClient = $this->createMock('Onurb\\Bundle\\YumlBundle\\Yuml\\YumlClientInterface');

        $yumlClient->expects($this->once())
            ->method('makeDslText')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $yumlClient->expects($this->once())
            ->method('getGraphUrl')
            ->will($this->returnValue(self::YUML_LINK));

        $this->container = $this->createMock('Symfony\\Component\\DependencyInjection\\ContainerInterface');

        $this->container->expects($this->once())->method('get')
            ->with($this->matches('onurb_yuml.client'))
                    ->will($this->returnValue($yumlClient));

        $this->container->expects($this->any())->method('getParameter')
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        switch ($arg) {
                            case 'onurb_yuml.show_fields_description':
                                return false;
                            case 'onurb_yuml.colors':
                            case 'onurb_yuml.notes':
                                return array();
                            default:
                                return false;
                        }
                    }
                )
            );

        $this->command->setContainer($this->container);

        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Command\YumlCommand
     */
    public function testExecute()
    {
        $this->commandTester->execute(array(
            'command'   => $this->command->getName()
        ));

        $this->assertRegExp('/.../', $this->commandTester->getDisplay());
        $this->assertSame('Downloaded', explode(' ', $this->commandTester->getDisplay())[0]);
    }
}
