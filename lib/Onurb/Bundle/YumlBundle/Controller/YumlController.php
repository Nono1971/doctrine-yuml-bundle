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
    public function indexAction()
    {
        $yumlClient = $this->get('onurb.doctrine_yuml.client');

        return $this->redirect($yumlClient->getGraphUrl($yumlClient->makeDslText()));
    }
}
