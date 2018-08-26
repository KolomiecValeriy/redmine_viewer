<?php

namespace RedmineViewerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redmine_viewer');

        $rootNode
            ->children()
                ->scalarNode('redmine_api_key')
                    ->isRequired()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
