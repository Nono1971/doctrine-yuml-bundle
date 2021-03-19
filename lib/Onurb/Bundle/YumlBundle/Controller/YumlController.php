<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Config\Config;
use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
use Onurb\Bundle\YumlBundle\Yuml\YumlClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Utility to generate Yuml compatible strings from metadata graphs
 * Adaptation of DoctrineORMModule\Yuml\YumlController for ZendFramework-Zend-Developer-Tools
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @author  Bruno Heron <herobrun@gmail.com>
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
class YumlController extends AbstractController
{

    /**
     * @var YumlClientInterface
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    public function __construct(YumlClientInterface $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $showDetailParam    = $this->config->getParameter('show_fields_description');
        $colorsParam        = $this->config->getParameter('colors');
        $notesParam         = $this->config->getParameter('notes');
        $styleParam         = $this->config->getParameter('style');
        $extensionParam     = $this->config->getParameter('extension');
        $directionParam     = $this->config->getParameter('direction');
        $scale              = $this->config->getParameter('scale');

        return $this->redirect(
            $this->client->getGraphUrl(
                $this->client->makeDslText($showDetailParam, $colorsParam, $notesParam),
                $styleParam,
                $extensionParam,
                $directionParam,
                $scale
            )
        );
    }
}
