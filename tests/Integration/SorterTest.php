<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\Bundle\IntlBundle\BudgegeriaIntlBundle;
use Budgegeria\Bundle\IntlBundle\DependencyInjection\BudgegeriaIntlExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class SorterTest extends TestCase
{
    public function testDefaultSorterBuilderService(): void
    {
        /** @var \Budgegeria\IntlSort\Builder $builder */
        $builder = $this->createContainer()->get('budgegeria_intl_bundle.sorter.builder.standard');
        $sorted = $builder->getSorter()->sort([2, 1, 3]);
        $expected = [
            1 => 1,
            0 => 2,
            2 => 3,
        ];

        self::assertSame($expected, $sorted);
    }

    private function createContainer() : ContainerBuilder
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
                'currency' => 'USD'
            ]
        ], $containerBuilder);

        $containerBuilder->getDefinition('budgegeria_intl_bundle.sorter.builder.standard')->setPublic(true);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
