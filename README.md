XML Document Parser for Laravel and PHP
==============

Parser Component is a framework agnostic package that provide a simple way to parse XML to array without having to write a complex logic.

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/parser.svg?style=flat)](https://packagist.org/packages/orchestra/parser)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/parser.svg?style=flat)](https://packagist.org/packages/orchestra/parser)
[![MIT License](https://img.shields.io/packagist/l/orchestra/parser.svg?style=flat)](https://packagist.org/packages/orchestra/parser)
[![Build Status](https://img.shields.io/travis/orchestral/parser/3.1.svg?style=flat)](https://travis-ci.org/orchestral/parser)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/parser/3.1.svg?style=flat)](https://coveralls.io/r/orchestral/parser?branch=3.1)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/parser/3.1.svg?style=flat)](https://scrutinizer-ci.com/g/orchestral/parser/)

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Examples](#examples)

## Version Compatibility

Laravel    | Parser
:----------|:----------
 4.1.x     | 2.1.x
 4.2.x     | 2.2.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/parser": "~3.0"
	}
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/parser=~3.0"

## Configuration

Next add the service provider in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\Parser\XmlServiceProvider::class,
],
```

## Examples

Here's a basic example how you can parse XML to simple array:

```xml
<api>
    <user followers="5">
        <id>1</id>
        <email>crynobone@gmail.com</email>
    </user>
</api>
```

```php
$app = new Illuminate\Container\Container;
$document = new Orchestra\Parser\Xml\Document($app);
$reader = new Orchestra\Parser\Xml\Reader($document);

$xml = $reader->load('path/to/above.xml');
$user = $xml->parse([
    'id' => ['uses' => 'user.id'],
    'email' => ['uses' => 'user.email'],
    'followers' => ['uses' => 'user::followers'],
]);
```
