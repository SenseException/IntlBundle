<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\IntlSort\Builder;
use Budgegeria\IntlSort\Sorter\Sorter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class SorterTest extends TestCase
{
    use Container;

    /** @test */
    public function defaultSorterBuilderService(): void
    {
        /** @var Builder $builder */
        $builder  = $this->createContainer()->get('budgegeria_intl_bundle.sorter.builder.standard');
        $sorted   = $builder->getSorter()->sort([2, 1, 3]);
        $expected = [
            1 => 1,
            0 => 2,
            2 => 3,
        ];

        self::assertSame($expected, $sorted);
    }

    /** @test */
    public function configuredSorterBuilderService(): void
    {
        /** @var Sorter $sorter */
        $sorter   = $this->createContainer()->get('budgegeria_intl_bundle.sorter.my_sorter');
        $sorted   = $sorter->sort(['a', 'y', 'ä']);
        $expected = [
            1 => 'y',
            2 => 'ä',
            0 => 'a',
        ];

        self::assertSame($expected, $sorted);
    }

    /** @test */
    public function configuredSorterBuilderServiceWithoutLocale(): void
    {
        /** @var Sorter $sorter */
        $sorter   = $this->createContainer()->get('budgegeria_intl_bundle.sorter.sorter_wo_locale');
        $sorted   = $sorter->sort(['a', 'y', 'ä']);
        $expected = [
            1 => 'y',
            2 => 'ä',
            0 => 'a',
        ];

        self::assertSame($expected, $sorted);
    }

    /** @test */
    public function internalSorterConfigIsRemovedAfterContainerIsCompiled(): void
    {
        $this->expectException(ParameterNotFoundException::class);
        $this->createContainer()->getParameter('budgegeria_intl.sorter');
    }
}
