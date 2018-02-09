<?php
namespace OnurbTest\Bundle\YumlBundle\Curl;

use Onurb\Bundle\YumlBundle\Curl\Curl;
use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{
    /**
     * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
     */
    public function testIsInstanceOf()
    {
        $testUrl = 'http://testUrl.test';
        $curl = curl_init();
        $curlClass = new Curl($testUrl, $curl);
        $this->assertInstanceOf('Onurb\\Bundle\\YumlBundle\\Curl\\CurlInterface', $curlClass);

        $this->assertSame($testUrl, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));

        curl_close($curl);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
     * @expectedException \Exception
     */
    public function testResponseWithWrongUrl()
    {
        $testUrl = 'http://localhost.test/url_that_doesnt_exists';
        $curl = new Curl($testUrl);

        $curl->getResponse();
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
     */
    public function testResponseWithCorrectUrl()
    {
        $testUrl = 'https://yuml.me';
        $curl = new Curl($testUrl);

        $response = $curl->getResponse();
        $this->assertSame('<!DOCTYPE', explode(' ', $response)[0]);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
     */
    public function testResponseWithPostData()
    {
        $testUrl = 'https://yuml.me/diagram/plain/class';
        $curl = new Curl($testUrl);

        $posts = array(
            'dsl_text' => '[Simple.Entity|+a;b;c]'
        );
        $curl->setPosts($posts);
        $response = $curl->getResponse();

        $this->assertSame('15a98c92.png', $response);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
     */
    public function testDowloadFile()
    {
        $fileUrl = 'https://yuml.me/15a98c92.png';
        $fileName = 'test.png';

        $this->assertFalse(file_exists($fileName));

        $curl = new Curl($fileUrl);
        $curl->setOutput($fileName);
        $curl->getResponse();

        $this->assertTrue(file_exists($fileName));
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }
}
