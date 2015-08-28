<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Mapping\ClassMetadata;

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
        $schema = $this->getGraphlUrl($dsl_text);

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
    private function getGraphlUrl($dsl_text){
        $poststring = $this->formatCurlPostPostString($dsl_text);
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, self::YUML_POST_URL);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$poststring);
        curl_setopt($curl,CURLOPT_TIMEOUT,15);
        $return = curl_exec($curl);
        curl_close($curl);

        return $return;
    }

    /**
     * @param string $dsl_text
     * @return string
     */
    private function formatCurlPostPostString($dsl_text){
        return 'dsl_text='.urlencode($dsl_text);
    }
}
