<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use function dirname;

class BudgegeriaIntlExtension extends Extension
{
    /** @param mixed[] $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('formatter.php');
        $loader->load('sorter.php');

        $configuration = new Configuration();
        /** @phpstan-var array{locale: string, currency: string, sorter: array<string, array<string, mixed>>} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('budgegeria_intl.locale', $config['locale']);
        $container->setParameter('budgegeria_intl.currency', $config['currency']);
        $container->setParameter('budgegeria_intl.sorter', $config['sorter']);
    }
}
