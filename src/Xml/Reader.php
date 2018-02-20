<?php

namespace Orchestra\Parser\Xml;

use SimpleXMLElement;
use Laravie\Parser\Xml\Reader as BaseReader;

class Reader extends BaseReader
{
    public function via(SimpleXMLElement $xml)
    {
        return $this->document->setContent($xml);
    }
}
