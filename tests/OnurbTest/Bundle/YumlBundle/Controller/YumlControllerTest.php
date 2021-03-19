<?php

namespace OnurbTest\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Config\Config;
use Onurb\Bundle\YumlBundle\Controller\YumlController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @covers \Onurb\Bundle\YumlBundle\Controller\YumlController
 */
class YumlControllerTest extends TestCase
{
    public function testIndexAction(): void
    {
        $controller = $this->createController();

        $response = $controller->indexAction();

        $this->assertTrue($response instanceof RedirectResponse);
    }

    protected function createController(): YumlController
    {
        $yumlClient = $this->createMock('Onurb\\Bundle\\YumlBundle\\Yuml\\YumlClientInterface');

        $yumlClient->expects($this->once())
            ->method('makeDslText')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $yumlClient->expects($this->once())
            ->method('getGraphUrl')
            ->will($this->returnValue('https://yuml.me/15a98c92.png'));

        return new YumlController($yumlClient, new Config());
    }
}
