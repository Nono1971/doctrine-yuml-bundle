<?php

namespace OnurbTest\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Controller\YumlController;
use Onurb\Bundle\YumlBundle\Yuml\YumlClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class YumlControllerTest extends TestCase
{
    /**
     * @covers \Onurb\Bundle\YumlBundle\Controller\YumlController
     */
    public function testIndexAction()
    {
        $controller = $this->createController();

        $response = $controller->indexAction();

        $this->assertTrue($response instanceof RedirectResponse);
    }

    protected function createController()
    {
        $config = [
            'show_fields_description' => false,
            'colors' => [],
            'notes' => [],
            'extension' => 'png',
            'style' => 'plain',
            'direction' => 'TB',
            'scale' => 'normal',
        ];

        $yumlClient = $this->createMock(YumlClientInterface::class);

        $yumlClient->expects($this->once())
            ->method('makeDslText')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $yumlClient->expects($this->once())
            ->method('getGraphUrl')
            ->will($this->returnValue('https://yuml.me/15a98c92.png'));

        $controller = new YumlController($yumlClient, $config);

        return($controller);
    }
}
