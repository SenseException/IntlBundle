<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection;

use Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass\SorterBuilderKeyIterator;
use Budgegeria\IntlSort\Builder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('budgegeria_intl');

        $rootNode = $treeBuilder->getRootNode();
        $this->buildGeneric($rootNode);
        $this->buildSorter($rootNode);

        return $treeBuilder;
    }

    private function buildGeneric(NodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('locale')->defaultValue('en_US')->end()
                ->scalarNode('currency')->defaultValue('USD')->end()
            ->end();
    }

    private function buildSorter(NodeDefinition $node): void
    {
        $sorterChildren = $node->children()
            ->arrayNode('sorter')
            ->arrayPrototype()
            ->children();

        foreach (new SorterBuilderKeyIterator(Builder::class) as $sorterConfigNames) {
            $sorterChildren->scalarNode($sorterConfigNames)->end();
        }

        $sorterChildren->scalarNode('locale')->end();

        $sorterChildren->end()
            ->end()
            ->end()
            ->end();
    }
}
