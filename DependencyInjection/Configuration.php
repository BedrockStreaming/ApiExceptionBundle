<?php

namespace M6Web\Bundle\ApiExceptionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('m6web_api_exception');

        $rootNode
            ->children()
                ->arrayNode('exception')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('stack_trace')->defaultValue(false)->end()
                        ->booleanNode('match_all')->defaultValue(true)->end()
                        ->arrayNode('default')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->integerNode('code')->defaultValue(0)->end()
                                ->integerNode('status')->defaultValue(500)->end()
                                ->scalarNode('message')->defaultValue('Internal server error')->end()
                                ->enumNode('level')
                                    ->values(['error', 'warning', 'notice'])
                                    ->defaultValue('error')
                                ->end()
                                ->arrayNode('headers')
                                    ->useAttributeAsKey('name')
                                    ->prototype('scalar')->end()
                                    ->defaultValue([])
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('exceptions')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->integerNode('code')->end()
                                    ->integerNode('status')->end()
                                    ->scalarNode('message')->end()
                                    ->enumNode('level')
                                        ->values(['error', 'warning', 'notice'])
                                    ->end()
                                    ->arrayNode('headers')
                                        ->useAttributeAsKey('name')
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('logger')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')->defaultValue(null)->end()
                        ->scalarNode('prefix')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
