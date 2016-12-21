<?php
namespace OnurbTest\Bundle\YumlBundle\Command;

use Onurb\Bundle\YumlBundle\Command\YumlCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class YumlCommandTest extends \PHPUnit_Framework_TestCase
{
    const YUML_LINK  = 'https://yuml.me/15a98c92.png';

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

        $this->commandTester = new CommandTester($this->command);

        $yumlClient = $this->getMock('Onurb\\Bundle\\YumlBundle\\Yuml\\YumlClientInterface');

        $yumlClient->expects($this->once())
            ->method('makeDslText')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $yumlClient->expects($this->once())
            ->method('getGraphUrl')
            ->will($this->returnValue(self::YUML_LINK));

        $this->container = $this->getMock('Symfony\\Component\\DependencyInjection\\ContainerInterface');

        $this->container->expects($this->once())->method('get')
            ->with($this->matches('onurb.doctrine_yuml.client'))
            ->will($this->returnValue($yumlClient));

        $this->command->setContainer($this->container);

    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Command\YumlCommand
     */
    public function testExecute()
    {
        $this->commandTester->execute(array('command' => $this->command->getName()));
        $this->assertRegExp('/.../', $this->commandTester->getDisplay());
        $this->assertSame('Downloaded', explode(' ', $this->commandTester->getDisplay())[0]);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Command\YumlCommand
     */
    public function testExecuteWithFilenameOption()
    {
        $filename = 'testFilename.png';
        $this->commandTester->execute(
            array(
                'command' => $this->command->getName(),
                '--filename' => $filename
            )
        );
        $this->assertSame(
            'Downloaded ' . self::YUML_LINK . ' to '. $filename,
            explode("\r\n",$this->commandTester->getDisplay())[0]
        );
    }
}
