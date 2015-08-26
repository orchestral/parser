<?php namespace Orchestra\Parser;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Contracts\Container\Container;

abstract class Document
{
    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The content.
     *
     * @var mixed
     */
    protected $content;

    /**
     * The original content.
     *
     * @var mixed
     */
    protected $originalContent;

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
     * Parse document.
     *
     * @param  array  $schema
     * @param  array  $config
     *
     * @return array
     */
    public function parse(array $schema, array $config = [])
    {
        $output = [];

        foreach ($schema as $key => $data) {
            $value = $this->parseData($data);

            if (! Arr::get($config, 'ignore', false)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Set the content.
     *
     * @param  mixed  $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content         = $content;
        $this->originalContent = $content;

        return $this;
    }

    /**
     * Get the content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get original content.
     *
     * @return mixed
     */
    public function getOriginalContent()
    {
        return $this->originalContent;
    }

    /**
     * Filter value.
     *
     * @param  mixed   $value
     * @param  string  $filter
     *
     * @return mixed
     */
    protected function filterValue($value, $filter)
    {
        $resolver = $this->getFilterResolver($filter);

        if (method_exists($resolver[0], $resolver[1])) {
            return $this->app->call($resolver, ['value' => $value]);
        }

        return $value;
    }

    /**
     * Resolve value from content.
     *
     * @param  array   $config
     * @param  string  $hash
     *
     * @return mixed
     */
    protected function resolveValue(array $config, $hash)
    {
        if (! isset($config['uses'])) {
            return $config['default'];
        }

        if (! is_array($config['uses'])) {
            return $this->getValue($this->getContent(), $config['uses'], $hash);
        }

        $values = [];

        foreach ($config['uses'] as $use) {
            $values[] = $this->getValue($this->getContent(), $use, $hash);
        }

        return $values;
    }

    /**
     * Resolve value from uses filter.
     *
     * @param  mixed   $content
     * @param  string  $use
     * @param  string  $default
     *
     * @return mixed
     */
    abstract protected function getValue($content, $use, $default = null);

    /**
     * Get filter resolver.
     *
     * @param  string  $filter
     *
     * @return array
     */
    protected function getFilterResolver($filter)
    {
        $class  = $filter;
        $method = 'filter';

        if (Str::startsWith($filter, '@')) {
            $method = 'filter'.Str::studly(substr($filter, 1));
            return [$this, $method];
        }

        if (Str::contains($filter, '@')) {
            list($class, $method) = explode('@', $filter, 2);
        }

        return [$this->app->make($class), $method];
    }

    /**
     * Parse single data.
     *
     * @param  mixed  $data
     *
     * @return mixed
     */
    protected function parseData($data)
    {
        $hash   = Str::random(60);
        $value  = $data;
        $filter = null;

        if (is_array($data)) {
            $value  = $this->resolveValue($data, $hash);
            $filter = Arr::get($data, 'filter');
        }

        if ($value === $hash) {
            $value = Arr::get($data, 'default');
        }

        if (! is_null($filter)) {
            $value = $this->filterValue($value, $filter);
        }

        return $value;
    }
}
