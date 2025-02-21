<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Tests\Integration;

use Budgegeria\Bundle\IntlBundle\Twig\IntlFormatterExtension;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\IntlFormatInterface;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    use Container;

    /** @test */
    public function intlFormatterService(): void
    {
        $container = $this->createContainer();

        $intlFormat = $container->get(IntlFormat::class);

        self::assertInstanceOf(IntlFormatInterface::class, $intlFormat);

        $date = new DateTime();
        $date->setDate(2016, 3, 1);
        $date->setTime(5, 30);
        $date->setTimezone(new DateTimeZone('US/Arizona'));

        self::assertSame('Today is 3/1/16', $intlFormat->format('Today is %date_short', $date));
        self::assertSame('I got 1,002.25 as average value', $intlFormat->format('I got %number as average value', 1002.25));
        self::assertSame('I got 1,002.2500 as average value', $intlFormat->format('I got %.4number as average value', 1002.25));
        self::assertSame('I got 001,002.2 as average value', $intlFormat->format('I got %09.1number as average value', 1002.25));
        self::assertSame('The timezone id is US/Arizona.', $intlFormat->format('The timezone id is %timeseries_id.', $date));
        self::assertSame('I am from Italy.', $intlFormat->format('I am from %region.', 'it_IT'));
        self::assertSame('You have 10$.', $intlFormat->format('You have 10%currency_symbol.', ''));
    }

    /** @test */
    public function twigExtensionService(): void
    {
        $container = $this->createContainer();

        $intlFormatterExtension = $container->get(IntlFormatterExtension::class);

        self::assertInstanceOf(IntlFormatterExtension::class, $intlFormatterExtension);
    }
}
