<?php
namespace OnurbTest\Bundle\YumlBundle\Curl;

use bovigo\vfs\vfsStream;
use Onurb\Bundle\YumlBundle\Curl\Curl;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Onurb\Bundle\YumlBundle\Curl\Curl
 */
class CurlTest extends TestCase
{
    public function testIsInstanceOf(): void
    {
        $testUrl = 'http://testUrl.test';
        $curl = curl_init();
        $curlClass = new Curl($testUrl, $curl);
        $this->assertInstanceOf('Onurb\\Bundle\\YumlBundle\\Curl\\CurlInterface', $curlClass);

        $this->assertSame($testUrl, curl_getinfo($curl, CURLINFO_EFFECTIVE_URL));

        curl_close($curl);
    }

    public function testResponseWithWrongUrl(): void
    {
        $testUrl = 'http://localhost.test/url_that_doesnt_exists';
        $curl = new Curl($testUrl);

        $this->expectException(\Exception::class);

        $curl->getResponse();
    }

    public function testResponseWithCorrectUrl(): void
    {
        $testUrl = 'https://yuml.me';
        $curl = new Curl($testUrl);

        $response = $curl->getResponse();
        $this->assertSame('<!DOCTYPE', explode(' ', $response)[0]);
    }

    public function testResponseWithPostData(): void
    {
        $testUrl = 'https://yuml.me/diagram/plain/class';
        $curl = new Curl($testUrl);

        $posts = array(
            'dsl_text' => '[Simple.Entity|+a;b;c]'
        );
        $curl->setPosts($posts);
        $response = $curl->getResponse();

        $this->assertSame('15a98c92.svg', $response);
    }

    public function testDowloadFile(): void
    {
        $vfsRoot = vfsStream::setup();

        $fileUrl = 'https://yuml.me/15a98c92.png';
        $fileName = 'test.png';
        $output = $vfsRoot->url() . '/' . $fileName;

        $this->assertFalse(file_exists($output));

        $curl = new Curl($fileUrl);
        $curl->setOutput($output);
        $curl->getResponse();

        $this->assertTrue(file_exists($output));
    }
}
