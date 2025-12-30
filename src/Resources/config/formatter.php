<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Budgegeria\Bundle\IntlBundle\Twig\IntlFormatterExtension;
use Budgegeria\IntlFormat\Formatter\CurrencySymbolFormatter;
use Budgegeria\IntlFormat\Formatter\ExceptionFormatter;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\Formatter\LocaleFormatter;
use Budgegeria\IntlFormat\Formatter\MessageFormatter;
use Budgegeria\IntlFormat\Formatter\PrecisionNumberFormatter;
use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\IntlFormatInterface;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->load('Budgegeria\\Bundle\\IntlBundle\\', '../../*')
        ->exclude(['../../{DependencyInjection,Resources}']);

    $services->instanceof(FormatterInterface::class)
        ->tag('budgegeria_intl.formatter')
        ->bind('$locale', '%budgegeria_intl.locale%');

    $services->set(CurrencySymbolFormatter::class);

    $services->set(ExceptionFormatter::class);

    $services->set(LocaleFormatter::class);

    $services->set(PrecisionNumberFormatter::class);

    $services->set(TimeZoneFormatter::class);

    $services->set('budgegeria_intl_bundle.message_number_formatter', MessageFormatter::class)
        ->factory([null, 'createNumberValueFormatter']);

    $services->set('budgegeria_intl_bundle.message_date_formatter', MessageFormatter::class)
        ->factory([null, 'createDateValueFormatter']);

    $services->set(SprintfParser::class);

    $services->alias(IntlFormatInterface::class, IntlFormat::class);

    $services->set(IntlFormat::class)
        ->args([
            tagged_iterator('budgegeria_intl.formatter'),
            service(SprintfParser::class),
        ]);

    $services->set(IntlFormatterExtension::class)
        ->bind('$currency', '%budgegeria_intl.currency%')
        ->tag('twig.extension');
};
