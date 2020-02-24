<?php

namespace Orchestra\Parser\Xml;

use Laravie\Parser\Xml\Reader as BaseReader;
use SimpleXMLElement;

class Reader extends BaseReader
{
    /**
     * Provides SimpleXMLElement to document.
     *
     * @return \Laravie\Parser\Document
     */
    public function via(SimpleXMLElement $xml)
    {
        return $this->document->setContent($xml);
    }
}
