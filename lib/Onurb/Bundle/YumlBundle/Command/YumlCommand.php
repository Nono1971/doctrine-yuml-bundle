<?php
namespace Onurb\Bundle\YumlBundle\Command;

use Onurb\Bundle\YumlBundle\Yuml\YumlClient;
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
        /** @var YumlClient $yumlClient */
        $yumlClient = $this->getContainer()->get('onurb_yuml.client');
        $filename = $input->getOption('filename');

        $showDetailParam = $this->getContainer()->getParameter('onurb_yuml.show_fields_description');
        $colorsParam = $this->getContainer()->getParameter('onurb_yuml.colors');
        $notesParam = $this->getContainer()->getParameter('onurb_yuml.notes');
        $styleParam = $this->getContainer()->getParameter('onurb_yuml.style');
        $extensionParam = $this->getContainer()->getParameter('onurb_yuml.extension');
        $direction = $this->getContainer()->getParameter('onurb_yuml.direction');
        $scale = $this->getContainer()->getParameter('onurb_yuml.scale');

        $graphUrl = $yumlClient->getGraphUrl(
            $yumlClient->makeDslText($showDetailParam, $colorsParam, $notesParam),
            $styleParam,
            $extensionParam,
            $direction,
            $scale
        );
        $yumlClient->downloadImage($graphUrl, $filename);

        $output->writeln(sprintf('Downloaded <info>%s</info> to <info>%s</info>', $graphUrl, $filename));
    }
}
