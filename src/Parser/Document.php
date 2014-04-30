<?php namespace Orchestra\Parser;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class Document
{
    /**
     * Container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Content.
     *
     * @var mixed
     */
    protected $content;

    /**
     * Construct a new document.
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }


    /**
     * Parse document.
     *
     * @param  array    $schema
     * @param  array    $config
     * @return \Illuminate\Support\Collection
     */
    public function parse(array $schema, array $config = [])
    {
        $output = new Collection;

        foreach ($schema as $key => $data) {
            $hash = Str::random(60);

            $value = $this->resolveValue($data, $hash);
            $filter = array_get($data, 'filter');

            if ($value === $hash) {
                $value = $data['default'];
            }

            if (! is_null($filter)) {
                $value = $this->filterValue($value, $filter);
            }

            if (! array_get($config, 'ignore', false)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * Set the content.
     *
     * @param  mixed $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @param $value
     * @param $filter
     * @return mixed
     */
    protected function filterValue($value, $filter)
    {
        $resolver = $this->getFilterResolver($filter);

        if (method_exists($resolver[0], $resolver[1])) {
            return call_user_func($resolver, $value);
        }

        return $value;
    }

    /**
     * Resolve value from content.
     * @param  $config
     * @param  $hash
     * @return mixed
     */
    protected function resolveValue($config, $hash)
    {
        if (! is_array($config)) {
            return $config;
        } elseif (! isset($config['uses'])) {
            return $config['default'];
        }

        if (is_array($config['uses'])) {
            $values = [];

            foreach ($config['uses'] as $use) {
                $values[] = $this->resolveValueByUses($use, $hash);
            }

            return $values;
        }

        return $this->resolveValueByUses($config['uses'], $hash);
    }

    /**
     * Resolve value from uses filter.
     *
     * @param  string   $use
     * @param  string   $default
     * @return mixed
     */
    abstract protected function resolveValueByUses($use, $default);

    /**
     * Get filter resolver.
     *
     * @param  string   $filter
     * @return array
     */
    protected function getFilterResolver($filter)
    {
        if (Str::startsWith($filter, '@')) {
            $filter = 'filter' . Str::studly(substr($filter, 1));

            return [$this, $filter];
            return $resolver;
        }

        list($class, $method) = explode('@', $filter, 2);

        is_null($method) && $method = 'filter';

        return [$this->app->make($class), $method];
    }
}
