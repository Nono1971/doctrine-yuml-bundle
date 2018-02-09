<?php
namespace OnurbTest\Bundle\YumlBundle\DependencyInjection;

use Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension;
use PHPUnit\Framework\TestCase;

class OnurbYumlExtensionTest extends TestCase
{

    /**
     * @covers \Onurb\Bundle\YumlBundle\DependencyInjection\OnurbYumlExtension
     */
    public function testIsInstanceOf()
    {
        $onurbYumlExtension = new OnurbYumlExtension();
        $this->assertInstanceOf("Symfony\\Component\\HttpKernel\\DependencyInjection\\Extension", $onurbYumlExtension);
    }
}
