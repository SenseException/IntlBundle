<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass;

use ArrayIterator;
use FilterIterator;
use ReflectionClass;
use ReflectionMethod;

use function assert;
use function is_string;
use function preg_replace;
use function strtolower;

class SorterBuilderKeyIterator extends FilterIterator
{
    /**
     * @phpstan-param class-string $className
     */
    public function __construct(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        $methods         = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        parent::__construct(new ArrayIterator($methods));
    }

    public function accept(): bool
    {
        return $this->current() !== 'get_sorter' &&
            $this->current() !== 'get_comparator' &&
            $this->current() !== 'create' &&
            $this->current() !== '__construct';
    }

    public function current(): string
    {
        /** @var ReflectionMethod $reflectionMethod */
        $reflectionMethod = parent::current();

        return $this->toUnderscore($reflectionMethod->getName());
    }

    private function toUnderscore(string $methodName): string
    {
        $methodName = preg_replace('/[A-Z]/', '_$0', $methodName);
        assert(is_string($methodName));

        return strtolower($methodName);
    }
}
