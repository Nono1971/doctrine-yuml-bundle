<?php
namespace Onurb\Bundle\YumlBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class YumlControllerTest extends WebTestCase
{
    public function testRedirect()
    {
        $client = static::createClient();
        $url = static::$kernel->getContainer()->get('router')->generate('doctrine_yuml_mapping');
        $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}