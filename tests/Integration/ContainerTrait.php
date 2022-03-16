<?php

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\Bundle\IntlBundle\BudgegeriaIntlBundle;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\BudgegeriaIntlExtension;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass\SorterConfigPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;

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
                ],
            ]
        ], $containerBuilder);

        (new SorterConfigPass())->process($containerBuilder);

        foreach ($containerBuilder->getDefinitions() as $definition) {
            $definition->setPublic(true);
        }

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
