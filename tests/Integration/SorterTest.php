<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    use ContainerTrait;

    /**
     * @test
     */
    public function defaultSorterBuilderService(): void
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
}
