<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Twig;

use Budgegeria\Bundle\IntlBundle\Twig\IntlFormatterExtension;
use Budgegeria\IntlFormat\Factory;
use Twig\Test\IntegrationTestCase;

class IntlExtensionTest extends IntegrationTestCase
{
    /** @return IntlFormatterExtension[] */
    public function getExtensions(): array
    {
        return [
            new IntlFormatterExtension((new Factory())->createIntlFormat('de_DE'), 'EUR'),
        ];
    }

    protected function getFixturesDir(): string
    {
        return __DIR__ . '/Fixtures/';
    }
}
