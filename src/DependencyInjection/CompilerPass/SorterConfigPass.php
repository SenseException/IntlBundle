<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass;

use Budgegeria\IntlSort\Builder;
use Budgegeria\IntlSort\Sorter\Sorter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SorterConfigPass implements CompilerPassInterface
{
    private const SERVICE_PREFIX = 'budgegeria_intl_bundle.sorter.';
    private const SERVICE_FACTORY_PREFIX = 'budgegeria_intl_bundle.factory.sorter.';

    public function process(ContainerBuilder $container): void
    {
        $defaultLocale = $container->getParameter('budgegeria_intl.locale');
        /** @phpstan-var array<string, array<string, mixed>> $sorterConfigs */
        $sorterConfigs = $container->getParameter('budgegeria_intl.sorter');
        $container->getParameterBag()->remove('budgegeria_intl.sorter');

        foreach ($sorterConfigs as $serviceSuffix => $sorterConfig) {
            /** @phpstan-var string $locale */
            $locale = $sorterConfig['locale'] ?? $defaultLocale;
            unset($sorterConfig['locale']);

            $definition = new Definition(Builder::class, [$locale]);

            /**
             * @var mixed $value
             */
            foreach ($sorterConfig as $methodName => $value) {
                $definition->addMethodCall($this->toCamelCase($methodName), [$value]);
            }

            $container->setDefinition(self::SERVICE_FACTORY_PREFIX.$serviceSuffix, $definition);

            $definitionFactory = new Definition(Sorter::class);
            $definitionFactory->setFactory([new Reference(self::SERVICE_FACTORY_PREFIX.$serviceSuffix), 'getSorter']);
            $definitionFactory->setPublic(true);

            $container->setDefinition(self::SERVICE_PREFIX.$serviceSuffix, $definitionFactory);
        }
    }

    private function toCamelCase(string $method): string
    {
        return str_replace('_', '', ucwords($method, '_'));
    }
}
