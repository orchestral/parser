<?php namespace Orchestra\Parser\Xml;

use SimpleXMLElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Orchestra\Parser\Document as BaseDocument;

class Document extends BaseDocument
{
    /**
     * {@inheritdoc}
     */
    public function parse(array $schema, array $config = [])
    {
        $base       = Arr::pull($config, 'base');
        $namespace  = Arr::pull($config, 'namespace');
        $document   = $this->content;
        $namespaces = $document->getNameSpaces(true);

        if (! is_null($base)) {
            $document = data_get($document, $base);
        }

        if (! is_null($namespace) && isset($namespaces[$namespace])) {
            $document = $document->children($namespaces[$namespace]);
        }

        $this->content = $document;

        return parent::parse($schema, $config);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue($content, $use, $default = null)
    {
        if (preg_match('/^(.*)\[(.*)\]$/', $use, $matches) && $content instanceof SimpleXMLElement) {
            return $this->getValueCollection($content, $matches, $default);
        } elseif (Str::contains($use, '::') && $content instanceof SimpleXMLElement) {
            return $this->getValueAttribute($content, $use, $default);
        }

        return $this->getValueData($content, $use, $default);
    }

    /**
     * Cast value to string only when it is an instance of SimpleXMLElement.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function castValue($value)
    {
        if ($value instanceof SimpleXMLElement) {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * Resolve value by uses as attribute.
     *
     * @param  \SimpleXMLElement  $content
     * @param  string  $use
     * @param  mixed  $default
     *
     * @return mixed
     */
    protected function getValueAttribute(SimpleXMLElement $content, $use, $default = null)
    {
        return $this->castValue($this->getRawValueAttribute($content, $use, $default));
    }

    /**
     * Resolve value by uses as attribute as raw.
     *
     * @param  \SimpleXMLElement  $content
     * @param  string  $use
     * @param  mixed  $default
     *
     * @return mixed
     */
    protected function getRawValueAttribute(SimpleXMLElement $content, $use, $default = null)
    {
        list($value, $attribute) = explode('::', $use, 2);

        if (is_null($parent = object_get($content, $value))) {
            return $default;
        }

        $attributes = $parent->attributes();

        return Arr::get($attributes, $attribute, $default);
    }

    /**
     * Resolve value by uses as data.
     *
     * @param  \SimpleXMLElement  $content
     * @param  string  $use
     * @param  mixed  $default
     *
     * @return mixed
     */
    protected function getValueData(SimpleXMLElement $content, $use, $default = null)
    {
        $value = $this->castValue(data_get($content, $use));

        if (empty($value) && ! in_array($value, ['0'])) {
            return $default;
        }

        return $value;
    }

    /**
     * Resolve values by collection.
     *
     * @param  \SimpleXMLElement  $content
     * @param  array  $matches
     * @param  mixed  $default
     *
     * @return array
     */
    protected function getValueCollection(SimpleXMLElement $content, array $matches, $default = null)
    {
        $collection = data_get($content, $matches[1]);
        $uses       = explode(',', $matches[2]);
        $values     = [];

        if (! $collection instanceof SimpleXMLElement) {
            return $default;
        }

        foreach ($collection as $content) {
            if (empty($content)) {
                continue;
            }

            $values[] = $this->parseValueCollection($content, $uses);
        }

        return $values;
    }

    /**
     * Resolve values by collection.
     *
     * @param  \SimpleXMLElement  $content
     * @param  array  $uses
     * @param  mixed  $default
     *
     * @return array
     */
    protected function parseValueCollection(SimpleXMLElement $content, array $uses)
    {
        $value = [];

        foreach ($uses as $use) {
            list($name, $as) = Str::contains($use, '>') ? explode('>', $use, 2) : [$use, $use];

            if (preg_match('/^([A-Za-z0-9_\-\.]+)\((.*)\=(.*)\)$/', $name, $matches)) {
                $item = $this->getSelfMatchingValue($content, $matches);

                if ($name == $as) {
                    $value = array_merge($value, $item);
                } else {
                    Arr::set($value, $as, $item);
                }
            } else {
                $name == '@' && $name = null;

                Arr::set($value, $as, $this->getValue($content, $name));
            }
        }

        return $value;
    }

    /**
     * Get self matching value.
     *
     * @param  \SimpleXMLElement  $content
     * @param  array  $matches
     *
     * @return array
     */
    protected function getSelfMatchingValue(SimpleXMLElement $content, array $matches = [])
    {
        $name = $matches[1];
        $key  = $matches[2];
        $meta = $matches[3];

        $item = [];

        $collection = $this->getValue($content, "{$name}[{$key},{$meta}]");

        foreach ($collection as $collect) {
            $k = $collect[$key];
            $v = $collect[$meta];

            $item[$k] = $v;
        }

        return $item;
    }
}
