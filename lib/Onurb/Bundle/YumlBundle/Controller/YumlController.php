<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
 */
class YumlController extends Controller
{
    const YUML_POST_URL = 'http://yuml.me/diagram/plain/class';
    const YUML_REDIRECT_URL = 'http://yuml.me/';

    public function indexAction()
    {
        return $this->redirect($this->getGraphUrl($this->makeDslText()));
    }

    /**
     * @return string
     */
    private function makeDslText()
    {
        return $this->generateGraph($this->getClasses());
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
     * @return array
     */
    private function getClasses()
    {
        $classes = array();
        /**
         * @var ClassMetadata $class
         */
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

    /**
     * @param string $dsl_text
     * @return string
     */
    private function getGraphUrl($dsl_text){
        $curl = new Curl(self::YUML_POST_URL);
        $curl->setPosts(array('dsl_text' => $dsl_text));
        $return = $curl->getResponse();

        return self::YUML_REDIRECT_URL . $return;
    }
}
