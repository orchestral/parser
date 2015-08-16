XML Document Parser for Laravel and PHP
==============

Parser Component is a framework agnostic package that provide a simple way to parse XML to array without having to write a complex logic.

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/parser.svg?style=flat-square)](https://packagist.org/packages/orchestra/parser)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/parser.svg?style=flat-square)](https://packagist.org/packages/orchestra/parser)
[![MIT License](https://img.shields.io/packagist/l/orchestra/parser.svg?style=flat-square)](https://packagist.org/packages/orchestra/parser)
[![Build Status](https://img.shields.io/travis/orchestral/parser/master.svg?style=flat-square)](https://travis-ci.org/orchestral/parser)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/parser/master.svg?style=flat-square)](https://coveralls.io/r/orchestral/parser?branch=master)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/parser/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/parser/)

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
 5.2.x     | 3.2.x@dev

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

### Aliases

You might want to add `Orchestra\Parser\Xml\Facade` to class aliases in `config/app.php`:

```php
'aliases' => [

    // ...

    'XmlParser' => Orchestra\Parser\Xml\Facade::class,
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

We can fetch the content as the following:

```php
$xml = XmlParser::load('path/to/above.xml');
$user = $xml->parse([
    'id' => ['uses' => 'user.id'],
    'email' => ['uses' => 'user.email'],
    'followers' => ['uses' => 'user::followers'],
]);
```

And this would be equivalent of:

```php
$user = [
    'id' => '1',
    'email' => 'crynobone@gmail.com',
    'followers' => '5'
];
```
