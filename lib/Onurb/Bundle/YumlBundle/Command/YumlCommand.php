<?php

namespace Onurb\Bundle\YumlBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate and save Yuml images for metadata graphs.
 *
 * @license MIT
 * @author Glynn Forrest <me@glynnforrest.com>
 **/
class YumlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('yuml:mappings')
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
        $yumlClient = $this->getContainer()->get('onurb.doctrine_yuml.client');
        $filename = $input->getOption('filename');

        $graphUrl = $yumlClient->getGraphUrl($yumlClient->makeDslText());
        $yumlClient->downloadImage($graphUrl, $filename);

        $output->writeln(sprintf('Downloaded <info>%s</info> to <info>%s</info>', $graphUrl, $filename));
    }
}
