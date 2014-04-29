Orchestra Platform Facile Component
==============

`Orchestra\Facile` simplify the need to create API based response in your Laravel 4 application.

[![Latest Stable Version](https://poser.pugx.org/orchestra/facile/v/stable.png)](https://packagist.org/packages/orchestra/facile) 
[![Total Downloads](https://poser.pugx.org/orchestra/facile/downloads.png)](https://packagist.org/packages/orchestra/facile) 
[![Build Status](https://travis-ci.org/orchestral/facile.png?branch=2.1)](https://travis-ci.org/orchestral/facile) 
[![Coverage Status](https://coveralls.io/repos/orchestral/facile/badge.png?branch=2.1)](https://coveralls.io/r/orchestral/facile?branch=2.1) 
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/facile/badges/quality-score.png?s=db660b406a7b1000f0431048d26f161793cb4b29)](https://scrutinizer-ci.com/g/orchestral/facile/) 

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/facile": "2.1.*"
	}
}
```

Next add the service provider in `app/config/app.php`.

```php
'providers' => array(

	// ...

	'Orchestra\Facile\FacileServiceProvider',
),
```

You might want to add `Orchestra\Support\Facades\Facile` to class aliases in `app/config/app.php`:

```php
'aliases' => array(

	// ...

	'Facile' => 'Orchestra\Support\Facades\Facile',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/facile)
* [Change Log](http://orchestraplatform.com/docs/latest/components/facile/changes#v2-1)
