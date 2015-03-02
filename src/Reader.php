<?php namespace Orchestra\Parser;

abstract class Reader
{
    /**
     * Document instance.
     *
     * @var \Orchestra\Parser\Document
     */
    protected $document;

    /**
     * Construct a new reader.
     *
     * @param  \Orchestra\Parser\Document  $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Extract content from string.
     *
     * @param  string  $content
     *
     * @return \Orchestra\Parser\Document
     */
    abstract public function extract($content);

    /**
     * Load content from file.
     *
     * @param  string  $filename
     *
     * @return \Orchestra\Parser\Document
     */
    abstract public function load($filename);
}
