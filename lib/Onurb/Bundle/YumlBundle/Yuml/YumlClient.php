<?php

namespace Onurb\Bundle\YumlBundle\Yuml;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\ClassMetadata;
use Onurb\Bundle\YumlBundle\MetadataGrapher\MetadataGrapher;
use Onurb\Bundle\YumlBundle\Curl\Curl;

/**
 * Utility to generate Yuml compatible strings from metadata graphs
 * Adaptation of DoctrineORMModule\Yuml\YumlController for ZendFramework-Zend-Developer-Tools
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Marco Pivetta <ocramius@gmail.com>
 * @author  Bruno Heron <herobrun@gmail.com>
 **/
class YumlClient
{
    const YUML_POST_URL = 'http://yuml.me/diagram/plain/class';
    const YUML_REDIRECT_URL = 'http://yuml.me/';

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get doctrine metadata as yuml.
     *
     * @return string
     */
    public function makeDslText()
    {
        return $this->generateGraph($this->getClasses());
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

        return self::YUML_REDIRECT_URL.$return;
    }

    /**
     * @return array
     */
    private function getMetadata()
    {
        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setEntityManager($this->entityManager);

        return $metadataFactory->getAllMetadata();
    }

    /**
     * @return array
     */
    private function getClasses()
    {
        $classes = array();
        /*
         * @var ClassMetadata
         */
        foreach ($this->getMetadata() as $class) {
            $classes[$class->getName()] = $class;
        }
        ksort($classes);

        return $classes;
    }

    /**
     * @param $classes
     *
     * @return string
     */
    private function generateGraph($classes)
    {
        $metagrapher = new MetadataGrapher();
        $graph = $metagrapher->generateFromMetadata($classes);

        return $graph;
    }
}
