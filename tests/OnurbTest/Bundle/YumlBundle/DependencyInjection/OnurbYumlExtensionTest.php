<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension;

class OnurbYumlExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension
     */
    public function testIsInstanceOf()
    {
        $onurbYumeExtension = new OnurbYumlExtension();
        $this->assertInstanceOf("Symfony\\Component\\HttpKernel\\DependencyInjection\\Extension", $onurbYumeExtension);
    }
}
