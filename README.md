XML Document Parser for Laravel and PHP
==============

Parser Component is a framework agnostic package that provide a simple way to parse XML to array without having to write a complex logic.

[![tests](https://github.com/orchestral/parser/workflows/tests/badge.svg?branch=7.x)](https://github.com/orchestral/parser/actions?query=workflow%3Atests+branch%3A7.x)
[![Latest Stable Version](https://poser.pugx.org/orchestra/parser/version)](https://packagist.org/packages/orchestra/parser)
[![Total Downloads](https://poser.pugx.org/orchestra/parser/downloads)](https://packagist.org/packages/orchestra/parser)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/parser/v/unstable)](//packagist.org/packages/orchestra/parser)
[![License](https://poser.pugx.org/orchestra/parser/license)](https://packagist.org/packages/orchestra/parser)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/parser/badge.svg?branch=7.x)](https://coveralls.io/github/orchestral/parser?branch=7.x)

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

## Version Compatibility

Laravel    | Parser
:----------|:----------
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x
 5.7.x     | 3.7.x
 5.8.x     | 3.8.x
 6.x       | 4.x
 7.x       | 5.x
 8.x       | 6.x 
 9.x       | 7.x

## Installation

To install through composer, run the following command from terminal:

    composer require "orchestra/parser"

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

