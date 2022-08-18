<?php

declare(strict_types = 1);

namespace Lingoda\BrazeBundle\DependencyInjection;

use Lingoda\BrazeBundle\Api\BrazeClientInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lingoda_braze');
        $treeBuilder
            ->getRootNode()
            ->children()
            ->scalarNode('api_key')->end()
            ->scalarNode('base_uri')->defaultValue(BrazeClientInterface::DEFAULT_BASE_URI)->end()
            ->scalarNode('http_client')->defaultNull()->end()
            ->scalarNode('http_client_max_retries')->defaultValue(3)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
