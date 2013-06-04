<?php

namespace Bafford\WebLibBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;


class WebLibCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bafford:weblib:install')
            ->setDescription('Create the web library directory based on configuration')
            ->addOption('symlink', null, InputOption::VALUE_NONE, 'Use symlinks (overrides configuration)')
            ->addOption('no-symlink', null, InputOption::VALUE_NONE, 'Do not use symlinks (overrides configuration)')
        ;
    }
    
    protected function installComponents(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $filesystem = $container->get('filesystem');
        
        $config = $container->getParameter('bafford_web_lib.config');
        
        $symlink = $config['symlink'];
        if($input->getOption('symlink') !== false)
            $symlink = true;
        else if($input->getOption('no-symlink') !== false)
            $symlink = false;
        
        $action = ($symlink ? 'link' : 'copy');
        
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
            
            $output->writeln("$action $dispSrc => $dispDest");
            
            $src = $vendorPath . $src;
            $dest = $libPath . $dest;
            
            if($filesystem->exists($dest))
                $filesystem->remove($dest);
            
            if($symlink)
                $filesystem->symlink($src, $dest);
            else
                $filesystem->mirror($src, $dest);
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
        $this->installComponents($input, $output);
    }
}
