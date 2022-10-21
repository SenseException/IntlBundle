<?php

declare(strict_types=1);

namespace Budgegeria\Bundle\IntlBundle\Twig;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\IntlFormatInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IntlFormatterExtension extends AbstractExtension
{
    public function __construct(private IntlFormatInterface $intlFormat, private string $currency)
    {
    }

    /** @return TwigFilter[] */
    public function getFilters(): array
    {
        return [
            new TwigFilter('intl_format', [$this, 'format']),
        ];
    }

    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('intl_format', [$this, 'format']),
            new TwigFunction('currency_symbol', function (): string {
                return $this->intlFormat->format('%currency_symbol', $this->currency);
            }),
        ];
    }

    /** @throws InvalidTypeSpecifierException */
    public function format(string $message, mixed ...$values): string
    {
        return $this->intlFormat->format($message, ...$values);
    }
}
