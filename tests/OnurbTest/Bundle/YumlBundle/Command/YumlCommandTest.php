<?php
namespace OnurbTest\Bundle\YumlBundle\Command;

use Onurb\Bundle\YumlBundle\Command\YumlCommand;

class YumlCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Onurb\Bundle\YumlBundle\Command\YumlCommand
     */
    public function testIsInstanceOf()
    {
        $command = new YumlCommand();

        $this->assertInstanceOf('Symfony\\Bundle\\FrameworkBundle\\Command\\ContainerAwareCommand', $command);
    }
}
