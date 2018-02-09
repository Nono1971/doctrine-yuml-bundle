<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
use Onurb\Bundle\YumlBundle\Yuml\YumlClientInterface;
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
     * @param YumlClientInterface|null $yumlClient
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(YumlClientInterface $yumlClient = null)
    {
        $yumlClient = $yumlClient ? $yumlClient : new YumlClient($this->getDoctrine()->getManager());

        $showDetailParam    = $this->container->getParameter('onurb_yuml.show_fields_description');
        $colorsParam        = $this->container->getParameter('onurb_yuml.colors');
        $notesParam         = $this->container->getParameter('onurb_yuml.notes');

        return $this->redirect(
            $yumlClient->getGraphUrl(
                $yumlClient->makeDslText($showDetailParam, $colorsParam, $notesParam)
            )
        );
    }
}
