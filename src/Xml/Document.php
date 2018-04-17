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
     * @param  string  $class
     * @param  string  $method
     *
     * @return array
     */
    protected function makeFilterResolver(string $class, string $method): array
    {
        return [$this->app->make($class), $method];
    }

    /**
     * Call filter to parse the value.
     *
     * @param  callable  $resolver
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function callFilterResolver(callable $resolver, $value)
    {
        return $this->app->call($resolver, ['value' => $value]);
    }
}
