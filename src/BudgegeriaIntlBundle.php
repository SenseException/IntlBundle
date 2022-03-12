<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle;

use Budgegeria\Bundle\IntlBundle\DependencyInjection\CompilerPass\SorterConfigPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BudgegeriaIntlBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SorterConfigPass());

        parent::build($container);
    }
}
