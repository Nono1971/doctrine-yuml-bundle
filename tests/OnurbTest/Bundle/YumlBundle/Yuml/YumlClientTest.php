<?php
namespace OnurbTest\Bundle\YumlBundle\Yuml;

use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
use PHPUnit\Framework\TestCase;

class YumlClientTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Yuml\YumlClient
     */
    public function testIsInstanceOf()
    {
        $entityManager = $this->createMock("Doctrine\\ORM\\EntityManagerInterface");
        $metadataFactory = $this->getMockBuilder("Doctrine\\Common\\Persistence\\Mapping\\ClassMetadataFactory")
            ->setMethods(array(
                'getAllMetadata',
                'getMetadataFor',
                'hasMetadataFor',
                'setMetadataFor',
                'isTransient',
                'setEntityManager',
            ))
            ->getMock();

        $metadataFactory->expects($this->once())->method('setEntityManager');

        $metadataGrapher = $this->getMockBuilder('Onurb\\Doctrine\\ORMMetadataGrapher\\YUMLMetadataGrapherInterface')
            ->getMock();

        $client = new YumlClient($entityManager, $metadataFactory, $metadataGrapher);
        $this->assertInstanceOf("Onurb\\Bundle\\YumlBundle\\Yuml\\YumlClient", $client);
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Yuml\YumlClient
     */
    public function testGetMetadata()
    {
        $entityManager = $this->createMock("Doctrine\\ORM\\EntityManagerInterface");

        $metadataFactory = $this->getMockBuilder("Doctrine\\Common\\Persistence\\Mapping\\ClassMetadataFactory")
            ->setMethods(array(
                'getAllMetadata',
                'getMetadataFor',
                'hasMetadataFor',
                'setMetadataFor',
                'isTransient',
                'setEntityManager',
            ))
            ->getMock();

        $metadataFactory->expects($this->once())->method('setEntityManager');

        $class = $this->createmock('Doctrine\\Common\\Persistence\\Mapping\\ClassMetadata');
        $class->expects($this->any())->method('getName')->will($this->returnValue('Simple\\Entity'));
        $class->expects($this->any())->method('getFieldNames')->will($this->returnValue(array('a', 'b', 'c')));
        $class->expects($this->any())->method('getAssociationNames')->will($this->returnValue(array()));
        $class->expects($this->any())->method('isIdentifier')->will(
            $this->returnCallback(
                function ($field) {
                    return $field === 'a';
                }
            )
        );

        $metadataFactory->expects($this->once())->method('getAllMetadata')->will($this->returnValue(array($class)));

        $metadataGrapher = $this->getMockBuilder('Onurb\\Doctrine\\ORMMetadataGrapher\\YUMLMetadataGrapherInterface')
            ->getMock();

        $metadataGrapher->expects($this->once())->method('generateFromMetadata')
            ->will($this->returnValue('[Simple.Entity|+a;b;c]'));

        $client = new YumlClient($entityManager, $metadataFactory, $metadataGrapher);

        $this->assertSame('[Simple.Entity|+a;b;c]', $client->makeDslText());
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Yuml\YumlClient
     */
    public function testGetGraphUrl()
    {
        $entityManager = $this->createMock("Doctrine\\ORM\\EntityManagerInterface");

        $client = new YumlClient($entityManager);

        $this->assertSame(
            'https://yuml.me/15a98c92.png',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'plain',
                'png',
                'TB',
                'normal'
            )
        );

        $this->assertSame(
            'https://yuml.me/15a98c92.jpg',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'plain',
                'jpg',
                'TB',
                'normal'
            )
        );

        $this->assertSame(
            'https://yuml.me/d6ba9ce1.png',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'plain',
                'png',
                'LR',
                'huge'
            )
        );

        $this->assertSame(
            'https://yuml.me/4f52303c.png',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'scruffy',
                'png',
                'LR',
                'big'
            )
        );

        $this->assertSame(
            'https://yuml.me/0df97f73.png',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'plain',
                'png',
                'RL',
                'small'
            )
        );

        $this->assertSame(
            'https://yuml.me/c066b235.svg',
            $client->getGraphUrl(
                '[Simple.Entity|+a;b;c]',
                'plain',
                'svg',
                'TB',
                'tiny'
            )
        );
    }

    /**
     * @covers \Onurb\Bundle\YumlBundle\Yuml\YumlClient
     */
    public function testDownloadFile()
    {
        $filename = 'test.png';
        $entityManager = $this->createMock("Doctrine\\ORM\\EntityManagerInterface");

        $client = new YumlClient($entityManager);

        $curl = $this->getMockBuilder('Onurb\\Bundle\\YumlBundle\\Curl\\CurlInterface')
            ->setMethods(array(
                '__construct',
                'setPosts',
                'setOutput',
                'getResponse',
            ))
            ->getMock();
        $curl->expects($this->once())->method('setOutput');
        $curl->expects($this->once())->method('getResponse')->will($this->returnValue(true));
        $result = $client->downloadImage('http://testUrl.test', $filename, $curl);
        $this->assertSame(true, $result);
    }
}
