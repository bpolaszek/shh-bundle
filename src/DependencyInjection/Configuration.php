<?php

namespace BenTools\Shh\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('shh');
        $rootNode = $treeBuilder->getRootNode();
        $configDir = '%kernel.project_dir%/config/shh';

        // @phpstan-ignore-next-line
        $rootNode
            ->children()
                ->scalarNode('private_key_file')->defaultValue($configDir . '/private.pem')->end()
                ->scalarNode('public_key_file')->defaultValue($configDir . '/public.pem')->end()
                ->scalarNode('passphrase')->defaultValue('%env(SHH_PASSPHRASE)%')->end()
            ->end();

        return $treeBuilder;
    }
}
