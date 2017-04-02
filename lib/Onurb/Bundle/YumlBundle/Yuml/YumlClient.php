<?php

namespace Onurb\Bundle\YumlBundle\Yuml;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory as ClassMetadataFactoryInterface;
use Onurb\Bundle\YumlBundle\Curl\Curl;
use Onurb\Bundle\YumlBundle\Curl\CurlInterface;
use Onurb\Doctrine\ORMMetadataGrapher\YUMLMetadataGrapher as MetadataGrapher;
use Onurb\Doctrine\ORMMetadataGrapher\YUMLMetadataGrapherInterface as MetadataGrapherInterface;

/**
 * Utility to generate Yuml compatible strings from metadata graphs
 * Adaptation of DoctrineORMModule\Yuml\YumlController for ZendFramework-Zend-Developer-Tools
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Marco Pivetta <ocramius@gmail.com>
 * @author  Bruno Heron <herobrun@gmail.com>
 **/
class YumlClient implements YumlClientInterface
{
    const YUML_POST_URL = 'https://yuml.me/diagram/plain/class';
    const YUML_REDIRECT_URL = 'https://yuml.me/';

    protected $entityManager;

    protected $metadataFactory;

    protected $metadataGrapher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param MetadataGrapherInterface|null $metadataGrapher
     */
    public function __construct(
        EntityManagerInterface          $entityManager,
        ClassMetadataFactoryInterface   $classMetadataFactory = null,
        MetadataGrapherInterface        $metadataGrapher = null
    ) {
        $this->entityManager = $entityManager;
        $this->metadataFactory = $classMetadataFactory ? $classMetadataFactory : new ClassMetadataFactory();
        $this->metadataFactory->setEntityManager($this->entityManager);
        $this->metadataGrapher = $metadataGrapher ? $metadataGrapher : new MetadataGrapher();
    }


    /**
     * Get doctrine metadata as yuml.
     *
     * @param bool $showDetail
     * @param array $colors
     * @param array $notes
     * @return string
     */
    public function makeDslText($showDetail = false, $colors = array(), $notes = array())
    {
        return $this->metadataGrapher->generateFromMetadata(
            $this->getClasses(),
            $showDetail,
            $colors,
            $notes
        );
    }

    /**
     * Use yuml.me to generate an image from yuml.
     *
     * @param string $dsl_text
     *
     * @return string The url of the generated image.
     */
    public function getGraphUrl($dsl_text)
    {
        $curl = new Curl(self::YUML_POST_URL);
        $curl->setPosts(array('dsl_text' => $dsl_text));
        $return = $curl->getResponse();

        return self::YUML_REDIRECT_URL . $return;
    }

    /**
     * @param string $graphUrl
     * @param string $filename
     * @return mixed
     */
    public function downloadImage($graphUrl, $filename, CurlInterface $curl = null)
    {
        $curl = $curl ? $curl : new Curl($graphUrl);
        $curl->setOutput($filename);

        return $curl->getResponse();
    }

    /**
     * @return array
     */
    private function getMetadata()
    {
        return $this->metadataFactory->getAllMetadata();
    }

    /**
     * @return array
     */
    private function getClasses()
    {
        $classes = array();
        /** @var ClassMetadata $class */
        foreach ($this->getMetadata() as $class) {
            $classes[$class->getName()] = $class;
        }
        ksort($classes);

        return $classes;
    }
}
