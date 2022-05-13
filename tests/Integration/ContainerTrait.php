<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\Bundle\IntlBundle\BudgegeriaIntlBundle;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\BudgegeriaIntlExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

use function sys_get_temp_dir;

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
            'kernel.root_dir'    => __DIR__ . '/../',
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
                    'sorter_wo_locale' => ['order_by_desc' => true],
                ],
            ],
        ], $containerBuilder);

        (new BudgegeriaIntlBundle())->build($containerBuilder);
        $publicCompilerPass = new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                foreach ($container->getDefinitions() as $definition) {
                    $definition->setPublic(true);
                }
            }
        };
        $containerBuilder->addCompilerPass($publicCompilerPass);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
