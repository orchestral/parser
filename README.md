Orchestra Platform Parser Component
==============

[![Latest Stable Version](https://poser.pugx.org/orchestra/parser/v/stable.png)](https://packagist.org/packages/orchestra/parser)
[![Total Downloads](https://poser.pugx.org/orchestra/parser/downloads.png)](https://packagist.org/packages/orchestra/parser)
[![Build Status](https://travis-ci.org/orchestral/parser.svg?branch=master)](https://travis-ci.org/orchestral/parser) 
[![Coverage Status](https://coveralls.io/repos/orchestral/parser/badge.png?branch=master)](https://coveralls.io/r/orchestral/parser?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/parser/badges/quality-score.png?s=1b9253efd488e1bd1fa15fe8f8b7ebc20c342d19)](https://scrutinizer-ci.com/g/orchestral/parser/)

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/parser": "2.2.*"
	}
}
```

## Example

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
