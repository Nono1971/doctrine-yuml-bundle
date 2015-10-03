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
        $this->downloadImage($graphUrl, $filename);

        $output->writeln(sprintf('Downloaded <info>%s</info> to <info>%s</info>', $graphUrl, $filename));
    }

    /**
     * @param string $graphUrl
     * @param string $filename
     */
    protected function downloadImage($graphUrl, $filename)
    {
        $curl = curl_init($graphUrl);
        $file = fopen($filename, 'w+');

        curl_setopt_array($curl, array(
            CURLOPT_URL => $graphUrl,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $file,
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception(curl_error($curl));
        }
    }
}
