<?php namespace Orchestra\Parser\Xml;

use SimpleXMLElement;
use Illuminate\Support\Str;
use Orchestra\Parser\Document as AbstractableDocument;

class Document extends AbstractableDocument
{
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
     * @param  mixed    $value
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
     * @param  \SimpleXMLElement    $content
     * @param  string               $use
     * @param  mixed                $default
     * @return mixed
     */
    protected function getValueAttribute(SimpleXMLElement $content, $use, $default = null)
    {
        list($value, $attribute) = explode('::', $use, 2);

        if (is_null($parent = object_get($content, $value))) {
            return $default;
        }

        $attributes = $parent->attributes();

        return $this->castValue(array_get($attributes, $attribute, $default));
    }

    /**
     * Resolve value by uses as data.
     *
     * @param  \SimpleXMLElement    $content
     * @param  string               $use
     * @param  mixed                $default
     * @return mixed
     */
    protected function getValueData(SimpleXMLElement $content, $use, $default = null)
    {
        $value = $this->castValue(data_get($content, $use));

        if (empty($value) && !in_array($value, array('0'))) {
            return $default;
        }

        return $value;
    }

    /**
     * Resolve values by collection.
     *
     * @param  \SimpleXMLElement    $content
     * @param  array                $matches
     * @param  mixed                $default
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
            $value = array();

            if (empty($content)) {
                continue;
            }

            foreach ($uses as $use) {
                array_set($value, $use, $this->getValue($content, $use));
            }

            $values[] = $value;
        }

        return $values;
    }
}
