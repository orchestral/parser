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
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Make filter resolver.
     */
    protected function makeFilterResolver(string $class, string $method): array
    {
        return [$this->app->make($class), $method];
    }

    /**
     * Call filter to parse the value.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function callFilterResolver(callable $resolver, $value)
    {
        return $this->app->call($resolver, ['value' => $value]);
    }
}
