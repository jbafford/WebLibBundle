<?php

namespace Bafford\WebLibBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\ArrayInput,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;


class WebLibCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bafford:weblib:install')
            ->setDescription('Create the web library directory based on configuration')
        ;
    }
    
    protected function installComponents(OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $config = $container->getParameter('bafford_web_lib.config');
        
        $baseDir = dirname($container->getParameter('kernel.root_dir'));
        
        $vendorDir = $baseDir . '/vendor/';
        $webDir = $baseDir . '/' . $config['libdir'] . '/';
        
        if(!file_exists($webDir))
            mkdir($webDir, 0755, true);
        
        foreach($config['contents'] as $src => $dest)
        {
            $src = $vendorDir . $src;
            $dest = $webDir . $dest;
            
            $output->writeln("link $src => $dest");
            
            if(file_exists($dest))
                @unlink($dest);
            
            symlink($src, $dest);
        }
    }
    
    /**
     * execute
     *
     * @param InputInterface  $input  instance
     * @param OutputInterface $output instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->installComponents($output);
    }
}
