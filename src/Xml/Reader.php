<?php

namespace Orchestra\Parser\Xml;

use SimpleXMLElement;
use Laravie\Parser\Xml\Reader as BaseReader;

class Reader extends BaseReader
{
    /**
     * Provides SimpleXMLElement to document.
     *
     * @param  \SimpleXMLElement  $xml
     *
     * @return \Laravie\Parser\Document
     */
    public function via(SimpleXMLElement $xml)
    {
        return $this->document->setContent($xml);
    }
}
