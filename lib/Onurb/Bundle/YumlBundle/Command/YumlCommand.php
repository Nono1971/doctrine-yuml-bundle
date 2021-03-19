<?php
namespace Onurb\Bundle\YumlBundle\Command;

use Onurb\Bundle\YumlBundle\Config\Config;
use Onurb\Bundle\YumlBundle\Yuml\YumlClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate and save Yuml images for metadata graphs.
 *
 * @license MIT
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class YumlCommand extends Command
{
    protected static $defaultName = 'yuml:mappings';

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
        parent::__construct();

        $this->client = $client;
        $this->config = $config;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate an image from yuml.me of doctrine metadata')
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                'Output filename',
                'yuml-mapping.png'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getOption('filename');

        $showDetailParam = $this->config->getParameter('show_fields_description');
        $colorsParam = $this->config->getParameter('colors');
        $notesParam = $this->config->getParameter('notes');
        $styleParam = $this->config->getParameter('style');
        $extensionParam = $this->config->getParameter('extension');
        $direction = $this->config->getParameter('direction');
        $scale = $this->config->getParameter('scale');

        $graphUrl = $this->client->getGraphUrl(
            $this->client->makeDslText($showDetailParam, $colorsParam, $notesParam),
            $styleParam,
            $extensionParam,
            $direction,
            $scale
        );
        $this->client->downloadImage($graphUrl, $filename);

        $output->writeln(sprintf('Downloaded <info>%s</info> to <info>%s</info>', $graphUrl, $filename));

        return 0;
    }
}
