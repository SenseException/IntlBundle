<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Budgegeria\IntlSort\Builder;
use Budgegeria\IntlSort\ComparatorFactory\Standard;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();

    $services->set('budgegeria_intl_bundle.sorter.factory.standard', Standard::class);

    $services->set('budgegeria_intl_bundle.sorter.builder.standard', Builder::class)
        ->args([
            '%budgegeria_intl.locale%',
            service('budgegeria_intl_bundle.sorter.factory.standard'),
        ]);
};
