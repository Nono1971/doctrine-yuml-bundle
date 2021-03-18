<?php

namespace Onurb\Bundle\YumlBundle\Controller;

use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
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
     * @var YumlClient
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    public function __construct(YumlClient $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $showDetailParam    = $this->config['show_fields_description'];
        $colorsParam        = $this->config['colors'];
        $notesParam         = $this->config['notes'];
        $styleParam         = $this->config['style'];
        $extensionParam     = $this->config['extension'];
        $directionParam     = $this->config['direction'];
        $scale              = $this->config['scale'];

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
