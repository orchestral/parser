<?php namespace Orchestra\Parser\Xml;

use SimpleXMLElement;
use Illuminate\Support\Str;
use Orchestra\Parser\Document as AbstractableDocument;

class Document extends AbstractableDocument
{
    /**
     * {@inheritdoc}
     */
    protected function resolveValueByUses($use, $default)
    {
        $content = $this->content;

        if (preg_match('/^(.*)\[(.*)\]$/', $use, $matches) && $content instanceof SimpleXMLElement) {
            return $this->getValueCollection($matches, $content, $default);
        } elseif (Str::contains($use, '::') && $content instanceof SimpleXMLElement) {
            return $this->getValueAttributeByUses($use, $content, $default);
        }

        return $this->getValueDataByUses($use, $content, $default);
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
     * @param  string               $use
     * @param  \SimpleXMLElement    $content
     * @param  mixed                $default
     * @return mixed
     */
    protected function getValueAttributeByUses($use, SimpleXMLElement $content, $default = null)
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
     * @param  string               $use
     * @param  \SimpleXMLElement    $content
     * @param  mixed                $default
     * @return mixed
     */
    protected function getValueDataByUses($use, SimpleXMLElement $content, $default = null)
    {
        $value = $this->castValue(data_get($content, $use));

        if (empty($value) && !in_array($value, ['0'])) {
            return $default;
        }

        return $value;
    }

    /**
     * Resolve values by collection.
     *
     * @param  array                $matches
     * @param  \SimpleXMLElement    $content
     * @param  mixed                $default
     * @return array
     */
    protected function getValueCollection(array $matches, SimpleXMLElement $content, $default = null)
    {
        $collection = data_get($content, $matches[1]);
        $uses       = explode(',', $matches[2]);
        $values     = [];

        if (! $collection instanceof SimpleXMLElement) {
            return $default;
        }

        foreach ($collection as $content) {
            $value = [];

            if (empty($content)) {
                continue;
            }

            foreach ($uses as $use) {
                array_set($value, $use, $this->getValueDataByUses($use, $content));
            }

            $values[] = $value;
        }

        return $values;
    }
}
