<?php namespace Orchestra\Parser\Xml;

use Orchestra\Parser\InvalidContentException;
use Orchestra\Parser\Reader as AbstractableReader;

class Reader extends AbstractableReader
{
    /**
     * {@inheritdoc}
     */
    public function extract($content)
    {
        $xml = @simplexml_load_string($content);

        return $this->resolveXmlObject($xml);
    }

    /**
     * {@inheritdoc}
     */
    public function load($filename)
    {
        $xml = @simplexml_load_file($filename);

        return $this->resolveXmlObject($xml);
    }

    /**
     * Validate given XML.
     *
     * @param  object $xml
     * @throws \Orchestra\Parser\InvalidContentException
     */
    protected function resolveXmlObject($xml)
    {
        if (!$xml) {
            throw new InvalidContentException("Unable to parse XML from string.");
        }

        $this->document->setContent($xml);

        return $this->document;
    }
}
