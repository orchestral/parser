<?php

namespace Orchestra\Parser\Xml;

use Illuminate\Contracts\Container\Container;
use Laravie\Parser\Xml\Document as BaseDocument;

class Document extends BaseDocument
{
    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Construct a new document.
     *
     * @param \Illuminate\Contracts\Container\Container  $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Make filter resolver.
     *
     * @param  array  $class
     * @param  mixed  $method
     *
     * @return array
     */
    protected function makeFilterResolver($class, $method)
    {
        return [$this->app->make($class), $method];
    }

    /**
     * Call filter to parse the value.
     *
     * @param  array  $resolver
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function callFilterResolver($resolver, $value)
    {
        return $this->app->call($resolver, ['value' => $value]);
    }
}
