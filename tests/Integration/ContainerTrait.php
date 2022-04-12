<?php

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\Bundle\IntlBundle\BudgegeriaIntlBundle;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\BudgegeriaIntlExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

trait ContainerTrait
{
    private function createContainer(): ContainerBuilder
    {
        $mappings = [
            'BudgegeriaIntlBundle' => BudgegeriaIntlBundle::class,
        ];

        $containerBuilder = new ContainerBuilder(new ParameterBag([
            'kernel.debug'       => false,
            'kernel.bundles'     => $mappings,
            'kernel.environment' => 'test',
            'kernel.root_dir'    => __DIR__.'/../',
            'kernel.name'        => 'test',
            'kernel.cache_dir'   => sys_get_temp_dir(),
        ]));

        $extension = new BudgegeriaIntlExtension();
        $containerBuilder->registerExtension($extension);
        $extension->load([
            'budgegeria_intl' => [
                'locale' => 'en_US',
                'currency' => 'USD',
                'sorter' => [
                    'my_sorter' => [
                        'order_by_desc' => true,
                        'locale' => 'de_DE',
                    ],
                    'sorter_wo_locale' => [
                        'order_by_desc' => true,
                    ],
                ],
            ]
        ], $containerBuilder);

        (new BudgegeriaIntlBundle())->build($containerBuilder);

        foreach ($containerBuilder->getDefinitions() as $definition) {
            $definition->setPublic(true);
        }

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
