<?php

namespace Onurb\Bundle\YumlBundle\Yuml;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory as ClassMetadataFactoryInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
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
 * @author  Bruno Heron <herobrun@gmail.com>
 * @author  Marco Pivetta <ocramius@gmail.com>
 **/
interface YumlClientInterface
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param MetadataGrapherInterface|null $metadataGrapher
     */
    public function __construct(
        EntityManagerInterface          $entityManager,
        ClassMetadataFactoryInterface   $classMetadataFactory = null,
        MetadataGrapherInterface        $metadataGrapher = null
    );

    /**
     * Get doctrine metadata as yuml.
     *
     * @param bool $showDetail
     * @param array $colors
     * @param array $notes
     * @return string The string from metadata, prepare for yuml API
     */
    public function makeDslText($showDetail = false, $colors = array(), $notes = array());

    /**
     * Use yuml.me to generate an image from yuml.
     *
     * @param string $dsl_text
     * @param string $style
     * @param string $extension
     * @param string $direction
     * @param string $scale
     *
     * @return string The url of the generated image.
     */
    public function getGraphUrl($dsl_text, $style, $extension, $direction, $scale);

    /**
     * @param string $graphUrl
     * @param string $filename
     * @param CurlInterface $curl
     * @return mixed
     */
    public function downloadImage($graphUrl, $filename, CurlInterface $curl = null);
}
