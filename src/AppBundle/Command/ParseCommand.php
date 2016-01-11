<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('vdm:parse')
            ->setDescription('parse le site viedemerde.fr')
           ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       
        $logger = $this->getContainer()->get('logger');
        
        //on appelle notre service
        //$vdm = $this->getContainer()->get('vdm')->getLatestPosts();
        
        //on écrit le résultat
        //$output->writeln("post count".array($vdm));
        $output->writeln("Hello World!");
    }
}

