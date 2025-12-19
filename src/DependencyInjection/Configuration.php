<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection;

use ArrayIterator;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass\SorterBuilderKeyIterator;
use Budgegeria\IntlSort\Builder;
use Override;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** @phpstan-return TreeBuilder<'array'> */
    #[Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('budgegeria_intl');

        $rootNode = $treeBuilder->getRootNode();
        $this->buildGeneric($rootNode);
        $this->buildSorter($rootNode);

        return $treeBuilder;
    }

    /** @phpstan-param ArrayNodeDefinition<TreeBuilder<'array'>> $node */
    private function buildGeneric(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('locale')->defaultValue('en_US')->end()
                ->scalarNode('currency')->defaultValue('USD')->end()
            ->end();
    }

    /** @phpstan-param ArrayNodeDefinition<TreeBuilder<'array'>> $node */
    private function buildSorter(ArrayNodeDefinition $node): void
    {
        $sorterChildren = $node->children()
            ->arrayNode('sorter')
            ->arrayPrototype()
            ->children();

        $methods = new ArrayIterator($this->getClassMethods(Builder::class));
        foreach (new SorterBuilderKeyIterator($methods) as $sorterConfigNames) {
            $sorterChildren->scalarNode((string) $sorterConfigNames)->end();
        }

        $sorterChildren->scalarNode('locale')->end();

        $sorterChildren->end()
            ->end()
            ->end()
            ->end();
    }

    /**
     * @phpstan-param class-string $className
     *
     * @phpstan-return list<ReflectionMethod>
     */
    private function getClassMethods(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);

        return $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
    }
}
