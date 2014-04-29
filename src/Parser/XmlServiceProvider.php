<?php namespace Orchestra\Parser;

use Illuminate\Support\ServiceProvider;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;

class XmlServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('orchestra.parser.reader', function ($app) {
            return new Reader(new Document($app));
        });
    }
}
