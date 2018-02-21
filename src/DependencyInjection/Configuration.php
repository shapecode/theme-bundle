<?php

namespace Shapecode\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class Configuration
 *
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @package Shapecode\Bundle\ThemeBundle\DependencyInjection
 *
 * @author Tobias Ebnöther <ebi@liip.ch>
 * @author Roland Schilter <roland.schilter@liip.ch>
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('liip_theme');
        $rootNode
            ->children()
                ->arrayNode('themes')
                    ->useAttributeAsKey('theme')
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('active_theme')->defaultNull()->end()
                ->arrayNode('path_patterns')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('app_resource')
                            ->useAttributeAsKey('path')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('bundle_resource')
                            ->useAttributeAsKey('path')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('bundle_resource_dir')
                            ->useAttributeAsKey('path')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cookie')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('theme_name')->cannotBeEmpty()->end()
                        ->scalarNode('lifetime')->defaultValue(31536000)->end()
                        ->scalarNode('path')->defaultValue('/')->end()
                        ->scalarNode('domain')->defaultValue('')->end()
                        ->booleanNode('secure')->defaultFalse()->end()
                        ->booleanNode('http_only')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
