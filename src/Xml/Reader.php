<?php namespace Orchestra\Parser\Xml;

use Orchestra\Parser\Reader as BaseReader;
use Orchestra\Parser\InvalidContentException;

class Reader extends BaseReader
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
     *
     * @return \Orchestra\Parser\Document
     *
     * @throws \Orchestra\Parser\InvalidContentException
     */
    protected function resolveXmlObject($xml)
    {
        if (! $xml) {
            throw new InvalidContentException('Unable to parse XML from string.');
        }

        return $this->document->setContent($xml);
    }
}
