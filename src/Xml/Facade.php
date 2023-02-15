<?php

namespace Orchestra\Parser\Xml;

/**
 * @method static \Laravie\Parser\Document extract(string $content)
 * @method static \Laravie\Parser\Document load(string $filename)
 * @method static \Laravie\Parser\Document local(string $filename)
 * @method static \Laravie\Parser\Document remote(string $filename)
 * @method static \Laravie\Parser\Document via(\SimpleXMLElement $xml)
 *
 * @see \Orchestra\Parser\Xml\Reader
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.parser.xml';
    }
}
