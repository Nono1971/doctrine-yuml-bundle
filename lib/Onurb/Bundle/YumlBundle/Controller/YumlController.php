<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\Mapping\ClassMetadataFactory;

use Onurb\Bundle\YumlBundle\MetadataGrapher\MetadataGrapher;


/**
 * Utility to generate Yuml compatible strings from metadata graphs
 * Adaptation of DoctrineORMModule\Yuml\YumlController for ZendFramework-Zend-Developer-Tools
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Marco Pivetta <ocramius@gmail.com>
 * @author  Bruno Heron <herobrun@gmail.com>
 */
class YumlController extends Controller
{
    const YUML_POST_URL = 'http://yuml.me/diagram/plain/class';
    const YUML_REDIRECT_URL = 'http://yuml.me/';

    public function indexAction()
    {
        $dsl_text = $this->makeDslText();

        $schema = $this->container
            ->get('buzz')
            ->post(
                self::YUML_POST_URL,
                array(),            //headers
                compact('dsl_text') //content
            )
            ->getContent()
        ;

        return $this->redirect(self::YUML_REDIRECT_URL . $schema);
    }

    /**
     * @return string
     */
    private function makeDslText()
    {
        $classes = $this->getClasses();
        return $this->generateGraph($classes);
    }

    /**
     * @return array
     */
    private function getMetadata()
    {
        $metadataFactory = new ClassMetadataFactory;
        $metadataFactory->setEntityManager($this->getDoctrine()->getManager());
        return $metadataFactory->getAllMetadata();
    }

    /**
     * @param $metadata
     * @return array
     */
    private function getClasses()
    {
        $classes = array();
        foreach ($this->getMetadata() as $class) {
            $classes[$class->getName()] = $class;
        }
        ksort($classes);
        return $classes;
    }

    /**
     * @param $classes
     * @return string
     */
    private function generateGraph($classes)
    {
        $metagrapher = new MetadataGrapher;
        $graph = $metagrapher->generateFromMetadata($classes);
        return $graph;
    }
}
