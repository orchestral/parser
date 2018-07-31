XML Document Parser for Laravel and PHP
==============

Parser Component is a framework agnostic package that provide a simple way to parse XML to array without having to write a complex logic.

[![Build Status](https://travis-ci.org/orchestral/parser.svg?branch=master)](https://travis-ci.org/orchestral/parser)
[![Latest Stable Version](https://poser.pugx.org/orchestra/parser/version)](https://packagist.org/packages/orchestra/parser)
[![Total Downloads](https://poser.pugx.org/orchestra/parser/downloads)](https://packagist.org/packages/orchestra/parser)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/parser/v/unstable)](//packagist.org/packages/orchestra/parser)
[![License](https://poser.pugx.org/orchestra/parser/license)](https://packagist.org/packages/orchestra/parser)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/parser/badge.svg?branch=3.6)](https://coveralls.io/github/orchestral/parser?branch=3.6)

Imagine if you can parse

```xml
<api>
    <user followers="5">
        <id>1</id>
        <email>crynobone@gmail.com</email>
    </user>
</api>
```

to

```php
$user = [
    'id' => '1',
    'email' => 'crynobone@gmail.com',
    'followers' => '5'
];
```

by just writing this:

```php
use Orchestra\Parser\Xml\Facade as XmlParser;

$xml = XmlParser::load('path/to/above.xml');
$user = $xml->parse([
    'id' => ['uses' => 'user.id'],
    'email' => ['uses' => 'user.email'],
    'followers' => ['uses' => 'user::followers'],
]);
```

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Changelog](https://github.com/orchestral/parser/releases)

## Version Compatibility

Laravel    | Parser
:----------|:----------
 4.x.x     | 2.x.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x
 5.4.x     | 3.4.x
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x
 5.7.x     | 3.7.x@dev

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

