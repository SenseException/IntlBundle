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

Example for configuring a sorter:

``` yaml
budgegeria_intl:
  locale: 'de_DE'
  currency: 'EUR'
  sorter:
    sorter_wo_locale:
      order_by_desc: ~
    my_sorter:
      order_by_desc: ~
      locale: 'de_DE'
```

`my_sorter` and `sorter_wo_locale` are free choosable keys that will be used to create new service ids
`budgegeria_intl_bundle.sorter.my_sorter` and `budgegeria_intl_bundle.sorter.sorter_wo_locale` which
can be used as dependencies.

``` php
class Foo
{
    /**
     * Injecting services "budgegeria_intl_bundle.sorter.my_sorter" or
     * "budgegeria_intl_bundle.sorter.sorter_wo_locale"
     */
    public function __construct(private Budgegeria\IntlSort\Sorter\Sorter $sorter)
    {
    }
    
    public function bar(): void
    {
        $sortedArray = $this->sorter->sort(['a', 'y', 'ä']);
    }
}
```

#### Available Configurations

Available are the method names of the `Budgegeria\IntlSort\Sorter\Sorter` class as underscore values.

* enable_french_collation
* disable_french_collation
* lower_case_first
* upper_case_first
* remove_case_first
* enable_normalization_mode
* disable_normalization_mode
* enable_numeric_collation
* disable_numeric_collation
* enable_case_level
* disable_case_level
* non_ignorable_alternate_handling
* shifted_alternate_handling
* primary_strength
* secondary_strength
* tertiary_strength
* quaternary_strength
* identical_strength
* order_by_asc
* order_by_desc
* order_by_keys
* order_by_values
* null_first
* null_last
* remove_null_position

Read more about the methods in the 
[Sorter documentation](https://senseexception.github.io/intl-sort/sorter-builder.html).
