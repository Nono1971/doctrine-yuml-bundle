<?php
namespace OnurbTest\Bundle\YumlBundle\DataCollector;

use Onurb\Bundle\YumlBundle\DataCollector\DoctrineYumlDataCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \Onurb\Bundle\YumlBundle\DataCollector\DoctrineYumlDataCollector
 */
class DoctrineYumlDataCollectorTest extends TestCase
{
    public function testIsInstanceOf(): void
    {
        $dataCollector = new DoctrineYumlDataCollector();
        $this->assertInstanceOf("Symfony\\Component\\HttpKernel\\DataCollector\\DataCollector", $dataCollector);
    }

    public function testGetName(): void
    {
        $dataCollector = new DoctrineYumlDataCollector();
        $this->assertSame('doctrine_yuml', $dataCollector->getName());
    }

    public function testCollect(): void
    {
        $dataCollector = new DoctrineYumlDataCollector();
        $dataCollector->collect(new Request(), new Response());

        $this->assertSame(
            "O:63:\"Onurb\\Bundle\\YumlBundle\\DataCollector\\DoctrineYumlDataCollector\""
            .":1:{s:7:\"\0*\0data\";a:1:{s:13:\"doctrine_yuml\";a:0:{}}}",
            serialize($dataCollector)
        );
    }

    public function testReset(): void
    {
        $dataCollector = new DoctrineYumlDataCollector();
        $dataCollector->collect(new Request(), new Response());

        $this->assertSame(
            "O:63:\"Onurb\\Bundle\\YumlBundle\\DataCollector\\DoctrineYumlDataCollector\""
            .":1:{s:7:\"\0*\0data\";a:1:{s:13:\"doctrine_yuml\";a:0:{}}}",
            serialize($dataCollector)
        );

        $dataCollector->reset();

        $this->assertSame(
            "O:63:\"Onurb\\Bundle\\YumlBundle\\DataCollector\\DoctrineYumlDataCollector\":1:{s:7:\"\0*\0data\";a:0:{}}",
            serialize($dataCollector)
        );
    }
}
