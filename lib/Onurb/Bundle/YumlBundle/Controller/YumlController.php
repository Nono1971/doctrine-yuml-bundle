<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Utility to generate Yuml compatible strings from metadata graphs
 * Adaptation of DoctrineORMModule\Yuml\YumlController for ZendFramework-Zend-Developer-Tools
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Bruno Heron <herobrun@gmail.com>
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
class YumlController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        /** @var YumlClient $yumlClient */
        $yumlClient = $this->get('onurb_yuml.client');

        $showDetailParam    = $this->container->getParameter('onurb_yuml.show_fields_description');
        $colorsParam        = $this->container->getParameter('onurb_yuml.colors');
        $notesParam         = $this->container->getParameter('onurb_yuml.notes');
        $styleParam         = $this->container->getParameter('onurb_yuml.style');
        $extensionParam     = $this->container->getParameter('onurb_yuml.extension');
        $directionParam     = $this->container->getParameter('onurb_yuml.direction');
        $scale              = $this->container->getParameter('onurb_yuml.scale');

        return $this->redirect(
            $yumlClient->getGraphUrl(
                $yumlClient->makeDslText($showDetailParam, $colorsParam, $notesParam),
                $styleParam,
                $extensionParam,
                $directionParam,
                $scale
            )
        );
    }
}
