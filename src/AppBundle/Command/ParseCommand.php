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
       
        $output->writeln("Let's parse some data from viedemerde.fr");
        $logger = $this->getContainer()->get('logger');
        
       
        //on appelle notre service
        $vdmResult = $this->getContainer()->get('vdm.parser')->getLatestPosts();
        
        // $output->writeln(var_dump($vdmResult));
        //on écrit le résultat
        //$output->writeln("post count".array($vdm));
       
    }
}

