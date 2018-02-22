<?php

namespace Onurb\Bundle\YumlBundle\Yuml;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory as ClassMetadataFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
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
class YumlClient implements YumlClientInterface
{
    const YUML_POST_URL = 'https://yuml.me/diagram/%STYLE%/class';
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
     * @param string $style the yuml style plain, boring or scruffy
     * @param string $extension the file extension to redirect to
     * @param string $direction the direction of the graph (LR,RL, TB)
     * @param string $scale the graph scale : huge, big, normal, small or tiny.
     *
     * @return string The url of the generated image.
     */
    public function getGraphUrl($dsl_text, $style, $extension, $direction, $scale)
    {
        $curl = new Curl($this->makePostUrl($style, $direction, $scale));
        $curl->setPosts(array('dsl_text' => $dsl_text));

        return self::YUML_REDIRECT_URL . $this->makeExtensionUrl($curl->getResponse(), $extension);
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
     * @return ClassMetadata[]
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

    /**
     * @param string $style
     * @return string
     */
    private function makePostUrl($style, $direction, $scale)
    {
        return str_replace('%STYLE%', $this->makeStyle($style, $direction, $scale), self::YUML_POST_URL);
    }

    /**
     * @param string $return
     * @param string $extension
     * @return string
     */
    private function makeExtensionUrl($return, $extension)
    {
        return explode('.', $return)[0] . '.' . $this->checkExtension($extension);
    }

    private function makeStyle($style, $direction, $scale)
    {
        return $this->checkStyle($style) . $this->makeDirection($direction) . $this->makeScale($scale);
    }

    /**
     * @param string $direction
     * @return string
     */
    private function makeDirection($direction)
    {
        switch ($direction) {
            case 'LR':
            case 'RL':
                return ';dir:' . $direction;
            default:
                return '';
        }
    }

    /**
     * @param string $scale
     * @return string
     */
    private function makeScale($scale)
    {
        switch ($scale) {
            case 'huge':
                return ';scale:180';
            case 'big':
                return ';scale:120';
            case 'small':
                return ';scale:80';
            case 'tiny':
                return ';scale:60';
            default:
                return '';
        }
    }

    /**
     * @param string $extension
     * @return string
     */
    private function checkExtension($extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'svg':
            case 'pdf':
            case 'json':
                return $extension;
            default:
                return 'png';
        }
    }

    private function checkStyle($style)
    {
        switch ($style) {
            case 'boring':
            case 'scruffy':
                return $style;
            default:
                return 'plain';
        }
    }
}
