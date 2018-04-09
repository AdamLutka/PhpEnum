# PHP implementation of enum [![Build Status](https://travis-ci.org/AdamLutka/PhpEnum.svg?branch=master)](https://travis-ci.org/AdamLutka/PhpEnum)

Enum types are represented by classes. Values of specific enum type are represented by @method annotations
that enables code completion and doesn't cause duplicity.

```php
<?php
require_once('vendor/autoload.php');

/**
 * @method static TypeEnum TYPE_1()
 * @method static $this TYPE_2()
 */
final class TypeEnum extends \AL\PhpEnum\Enum {}


var_dump((string)TypeEnum::TYPE_2());                        // string(6) "TYPE_2"
var_dump(TypeEnum::TYPE_2()->getValue());                    // string(6) "TYPE_2"
var_dump(TypeEnum::TYPE_1()->getOrder());                    // int(0)
var_dump(TypeEnum::TYPE_2()->getOrder());                    // int(1)
var_dump(TypeEnum::TYPE_2() === TypeEnum::TYPE_2());         // bool(true)
var_dump(TypeEnum::TYPE_2() == TypeEnum::TYPE_2());          // bool(true)
var_dump(TypeEnum::TYPE_1() === TypeEnum::TYPE_2());         // bool(false)
var_dump(TypeEnum::TYPE_1() == TypeEnum::TYPE_2());          // bool(false)
var_dump(TypeEnum::parse('TYPE_2') === TypeEnum::TYPE_2());  // bool(true)
var_dump(TypeEnum::tryParse('NOT_EXIST'));                   // NULL
```

## Getting Started

### Prerequisites

The code needs PHP 7.1 or greater.

### Installing

```
composer require AdamLutka/PhpEnum
```

## Running the tests

```
composer install
vendor/bin/phpunit
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/AdamLutka/PhpEnum/tags).
