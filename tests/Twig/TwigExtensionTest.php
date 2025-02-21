<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Twig;

use Budgegeria\Bundle\IntlBundle\Twig\IntlFormatterExtension;
use Budgegeria\IntlFormat\Factory;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigExtensionTest extends TestCase
{
    /** @test */
    public function intlFormatFilterFormat(): void
    {
        $twig = $this->createTwigEnv();

        self::assertInstanceOf(Environment::class, $twig);

        self::assertSame('This is the 4 time that the number 6.000 appear', $twig->render('intlformat-filter-format'));
    }

    /** @test */
    public function intlFormatFunctionFormat(): void
    {
        $twig = $this->createTwigEnv();

        self::assertInstanceOf(Environment::class, $twig);

        self::assertSame('This is the 4 time that the number 6.000 appear', $twig->render('intlformat-function-format'));
    }

    /** @test */
    public function intlFormatCurrency(): void
    {
        $twig = $this->createTwigEnv();

        self::assertInstanceOf(Environment::class, $twig);

        self::assertSame('€', $twig->render('intlformat-function-curreny'));
    }

    private function createTwigEnv(): Environment
    {
        $templates = [
            'intlformat-filter-format' => '{{ "This is the %integer time that the number %integer appear"|intl_format(4, 6000) }}',
            'intlformat-function-format' => '{{ intl_format("This is the %integer time that the number %integer appear", 4, 6000) }}',
            'intlformat-function-curreny' => '{{ currency_symbol() }}',
        ];

        $loader = new ArrayLoader($templates);
        $twig   = new Environment($loader);
        $twig->addExtension(new IntlFormatterExtension((new Factory())->createIntlFormat('de_DE'), 'EUR'));

        return $twig;
    }
}
