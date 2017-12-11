<?php

namespace Derdziak\ProductBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RatesDownloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rates:download')
            ->setDescription('Get latest rates from http://fixer.io/')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()
            ->get('derdziak_product.rates.downloader')
            ->download();
    }

}
