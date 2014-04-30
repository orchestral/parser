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

        if (Str::contains($use, '::') && $content instanceof SimpleXMLElement) {
            list($value, $attribute) = explode('::', $use, 2);

            if (is_null($parent = object_get($content, $value))) {
                return $default;
            }

            $attributes = $parent->attributes();

            return $this->castValue(array_get($attributes, $attribute, $default));
        }

        $value = $this->castValue(object_get($content, $use));

        if (empty($value)) {
            return $default;
        }

        return $value;
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
}
