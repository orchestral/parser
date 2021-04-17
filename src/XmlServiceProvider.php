<?php

namespace Orchestra\Parser;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Orchestra\Parser\Xml\Document as XmlDocument;
use Orchestra\Parser\Xml\Reader as XmlReader;

class XmlServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('orchestra.parser.xml', static function (Container $app) {
            return new XmlReader(new XmlDocument($app));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['orchestra.parser.xml'];
    }
}
