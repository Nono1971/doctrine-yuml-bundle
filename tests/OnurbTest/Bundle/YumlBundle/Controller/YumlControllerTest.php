<?php

namespace OnurbTest\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Controller\YumlController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class YumlControllerTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $controllerName = 'Onurb\\Bundle\\YumlBundle\\Controller\\YumlController';

    /**
     * @covers \Onurb\Bundle\YumlBundle\Controller\YumlController
     */
    public function testIndexAction()
    {
        $yumlClient = $this->createMock('Onurb\\Bundle\\YumlBundle\\Yuml\\YumlClientInterface');

        $yumlClient->expects($this->once())
            ->method('makeDslText')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $yumlClient->expects($this->once())
            ->method('getGraphUrl')
            ->will($this->returnValue('https://yuml.me/15a98c92.png'));

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
                            case 'onurb_yuml.extension':
                                return 'png';
                            case 'onurb_yuml.style':
                                return 'plain';
                            case 'onurb_yuml.direction':
                                return 'TB';
                            case 'onurb_yuml.scale':
                                return 'normal';
                            default:
                                return false;
                        }
                    }
                )
            );

        $controller = $this->createController();

        $response = $controller->indexAction();

        $this->assertTrue($response instanceof RedirectResponse);
    }

    protected function createController()
    {
        /**
         * @var YumlController $controller
         */
        $controller = new $this->controllerName;
        $controller->setContainer($this->container);

        return($controller);
    }
}
