<?php

namespace Orchestra\Parser;

use Illuminate\Support\ServiceProvider;
use Orchestra\Parser\Xml\Reader as XmlReader;
use Orchestra\Parser\Xml\Document as XmlDocument;
use Illuminate\Contracts\Support\DeferrableProvider;

class XmlServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('orchestra.parser.xml', function ($app) {
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
