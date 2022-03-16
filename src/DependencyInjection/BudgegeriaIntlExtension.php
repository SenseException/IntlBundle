<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BudgegeriaIntlExtension extends Extension
{
    /**
     * @param mixed[]          $configs
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('formatter.xml');
        $loader->load('sorter.xml');

        $configuration = new Configuration();
        /** @phpstan-var array{locale: string, currency: string, sorter: array<string, array<string, mixed>>} $config */
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('budgegeria_intl.locale', $config['locale']);
        $container->setParameter('budgegeria_intl.currency', $config['currency']);
        $container->setParameter('budgegeria_intl.sorter', $config['sorter']);
    }
}
