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
    public function indexAction()
    {
        $metadataFactory = new ClassMetadataFactory;
        $metadataFactory->setEntityManager($this->getDoctrine()->getManager());
        $metadata = $metadataFactory->getAllMetadata();

        $classes = array();
        foreach ($metadata as  $class) {
            $classes[$class->getName()] = $class;
        }
        ksort($classes);

        $metagrapher = new MetadataGrapher;
        $dsl_text = $metagrapher->generateFromMetadata($classes);

        $schema = $this->container
            ->get('buzz')
            ->post(
                'http://yuml.me/diagram/plain/class',
                array(),            //headers
                compact('dsl_text') //content
            )
            ->getContent()
        ;

        return $this->redirect('http://yuml.me/'. $schema);
    }
}
