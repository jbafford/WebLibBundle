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
        
        $basePath = realpath($container->getParameter('kernel.root_dir') . '/..') . '/';
        
        $targetDir = $config['target_dir'] . '/';
        $targetPath = $basePath . $targetDir;
        
        if(!$filesystem->exists($targetPath))
            $filesystem->mkdir($targetPath, 0755);
        
        foreach($config['contents'] as $item)
        {
            $src = $item['source'];
            $dest = $item['destination'];
            $files = $item['files'] ?? null;
            
            $dispSrc = $src;
            $dispDest = $targetDir . $dest;
            
            $output->writeln("$action $dispSrc => $dispDest");
            
            $src = $basePath . $src;
            $dest = $targetPath . $dest;
            
            if($filesystem->exists($dest))
                $filesystem->remove($dest);
            
            if($files)
            {
                $filesystem->mkdir($dest, 0755);
                
                foreach($files as $file)
                {
                    if($symlink)
                        $filesystem->symlink($src . '/' . $file, $dest . '/' . $file);
                    else
                        $filesystem->mirror($src . '/' . $file, $dest . '/' . $file);
                }
            }
            else if($symlink)
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
