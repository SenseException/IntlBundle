<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass;

use ArrayIterator;
use FilterIterator;
use ReflectionMethod;

use function assert;
use function in_array;
use function is_string;
use function preg_replace;
use function strtolower;

/**
 * @template TKey
 * @template T
 * @template TIterator as ArrayIterator<int, ReflectionMethod>
 * @extends FilterIterator<TKey, T, TIterator>
 */
class SorterBuilderKeyIterator extends FilterIterator
{
    public function accept(): bool
    {
        return ! in_array($this->current(), [
            'get_sorter',
            'get_comparator',
            'create',
            '__construct',
        ], true);
    }

    public function current(): string
    {
        $reflectionMethod = parent::current();
        assert($reflectionMethod instanceof ReflectionMethod);

        return $this->toUnderscore($reflectionMethod->getName());
    }

    private function toUnderscore(string $methodName): string
    {
        $methodName = preg_replace('/[A-Z]/', '_$0', $methodName);
        assert(is_string($methodName));

        return strtolower($methodName);
    }
}
