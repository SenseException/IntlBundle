<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\DependencyInjection\CompilerPass;

use Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass\SorterBuilderKeyIterator;
use Budgegeria\IntlSort\Builder;
use PHPUnit\Framework\TestCase;

use function array_values;
use function iterator_to_array;

class SorterBuilderKeyIteratorTest extends TestCase
{
    public function testIterationContent(): void
    {
        $sorterKeys = iterator_to_array(new SorterBuilderKeyIterator(Builder::class));
        $expected   = [
            'enable_french_collation',
            'disable_french_collation',
            'lower_case_first',
            'upper_case_first',
            'remove_case_first',
            'enable_normalization_mode',
            'disable_normalization_mode',
            'enable_numeric_collation',
            'disable_numeric_collation',
            'enable_case_level',
            'disable_case_level',
            'non_ignorable_alternate_handling',
            'shifted_alternate_handling',
            'primary_strength',
            'secondary_strength',
            'tertiary_strength',
            'quaternary_strength',
            'identical_strength',
            'order_by_asc',
            'order_by_desc',
            'order_by_keys',
            'order_by_values',
            'null_first',
            'null_last',
            'remove_null_position',
            'keep_keys',
            'omit_keys',
        ];

        self::assertEquals($expected, array_values($sorterKeys));
    }
}
