<?php
namespace OnurbTest\Bundle\YumlBundle\Curl;

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
        $vfsRoot = $this->getVirtualFileSystemRoot();

        $fileUrl = 'https://yuml.me/15a98c92.png';
        $fileName = 'test.png';
        $path = $vfsRoot . '/' . $fileName;

        $this->assertFalse(file_exists($path));

        $curl = new Curl($fileUrl);
        $curl->setOutput($path);
        $curl->getResponse();

        $this->assertTrue(file_exists($path));
    }

    /**
     * Creates a virtual file system and returned the root location prefixed with virtual file handler protocol
     */
    private function getVirtualFileSystemRoot(): string
    {
        if (class_exists('bovigo\vfs\vfsStream')) {
            return \bovigo\vfs\vfsStream::setup()->url();
        } elseif (class_exists('\org\bovigo\vfs\vfsStream')) {
            \org\bovigo\vfs\vfsStream::setup();
            return \org\bovigo\vfs\vfsStream::url('root');
        } else {
            throw new \LogicException(
                'Missing virtual file system dependency. '
                . 'Make sure "mikey179/vfsstream" is installed in composer.json'
            );
        }
    }
}
