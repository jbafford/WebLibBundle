<?php

namespace Bafford\WebLibBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bafford_web_lib');
        
        $rootNode
            ->children()
            	->scalarNode('target_dir')->isRequired()->cannotBeEmpty()->end()
            	->booleanNode('symlink')->defaultTrue()->end()
            	->arrayNode('contents')->isRequired()
            	    ->prototype('array')
            	        ->children()
            	            ->scalarNode('source')->isRequired()->cannotBeEmpty()->end()
            	            ->scalarNode('destination')->isRequired()->cannotBeEmpty()->end()
            	            ->arrayNode('files')->prototype('scalar')->end()
            	        ->end()
            	    ->end()
            	->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
