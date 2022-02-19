# IntlBundle

Improved and simple Intl implementations for Symfony.

[![Latest Stable Version](http://poser.pugx.org/senseexception/intl-bundle/v)](https://packagist.org/packages/senseexception/intl-bundle)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/senseexception/intl-bundle.svg)](https://packagist.org/packages/senseexception/intl-bundle)
[![Tests](https://github.com/SenseException/IntlBundle/actions/workflows/tests.yml/badge.svg)](https://github.com/SenseException/IntlBundle/actions/workflows/tests.yml)
[![Static Analysis](https://github.com/SenseException/IntlBundle/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/SenseException/IntlBundle/actions/workflows/static-analysis.yml)
[![License](http://poser.pugx.org/senseexception/intl-bundle/license)](https://packagist.org/packages/senseexception/intl-bundle)

## Installation

You can install it with [Composer](https://getcomposer.org/).

```
composer require senseexception/intl-bundle
```

If the composer installation with symfony/flex didn't already register the bundle, you need to register it into your
bundles.php manually:

``` php
return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Budgegeria\Bundle\IntlBundle\BudgegeriaIntlBundle::class => ['all' => true],
    // ...
];
```

## Configuration

By default a configuration doesn't need to be added if the needed locale is `en_US` and `USD` the currency. For any other
locale or currency you can add the following configuration to your project and configure the needed locale and currency
values:

``` yaml
budgegeria_intl:
  locale: 'de_DE'
  currency: 'EUR'
```

## Usage

### Formatter

#### Filters

Internationalization text formatting:
``` twig
{{ "This is the %ordinal time that the number %integer appear"|intl_format(4, 6000) }}
{# This is the 4th time that the number 6.000 appear #}
```

#### Functions

Internationalization text formatting:
``` twig
{{ intl_format("This is the %ordinal time that the number %integer appear", 4, 6000) }}
{# This is the 4th time that the number 6.000 appear #}
```

Currency symbol of configured locale:
``` twig
{{ currency_symbol() }}
{# € #}
```

### Sorter

tbd.
