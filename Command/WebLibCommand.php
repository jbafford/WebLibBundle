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
        $filesystem = $container->get('filesystem');
        
        $config = $container->getParameter('bafford_web_lib.config');
        
        $baseDir = dirname($container->getParameter('kernel.root_dir'));
        
        $vendorDir = 'vendor/';
        $libDir = $config['libdir'] . '/';
        
        $vendorPath = $baseDir . '/' . $vendorDir;
        $libPath = $baseDir . '/' . $libDir;
        
        if(!$filesystem->exists($libPath))
            $filesystem->mkdir($libPath, 0755);
        
        foreach($config['contents'] as $src => $dest)
        {
            $dispSrc = $vendorDir . $src;
            $dispDest = $libDir . $dest;
            
            $output->writeln("link $dispSrc => $dispDest");
            
            $src = $vendorPath . $src;
            $dest = $libPath . $dest;
            
            if($filesystem->exists($dest))
                $filesystem->remove($dest);
            
            $filesystem->symlink($src, $dest);
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
