<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass;

use Budgegeria\IntlSort\Builder;
use Budgegeria\IntlSort\Sorter\Sorter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use function array_keys;
use function str_replace;
use function ucwords;

class SorterConfigPass implements CompilerPassInterface
{
    private const SERVICE_PREFIX         = 'budgegeria_intl_bundle.sorter.';
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

            $factory           = $container->getDefinition('budgegeria_intl_bundle.sorter.factory.standard');
            $builderDefinition = new Definition(Builder::class, [$locale, $factory]);

            foreach (array_keys($sorterConfig) as $methodName) {
                $builderDefinition->addMethodCall($this->toCamelCase($methodName));
            }

            $factoryServiceName = self::SERVICE_FACTORY_PREFIX . $serviceSuffix;

            $container->setDefinition($factoryServiceName, $builderDefinition);

            $factoryDefinition = new Definition(Sorter::class);
            $factoryDefinition->setFactory([new Reference($factoryServiceName), 'getSorter']);

            $container->setDefinition(self::SERVICE_PREFIX . $serviceSuffix, $factoryDefinition);
        }
    }

    private function toCamelCase(string $method): string
    {
        return str_replace('_', '', ucwords($method, '_'));
    }
}
