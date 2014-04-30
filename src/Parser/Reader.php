<?php namespace Orchestra\Parser;


abstract class Reader
{
    /**
     * Document instance.
     *
     * @var Document
     */
    protected $document;

    /**
     * Construct a new reader.
     *
     * @param  Document $document
     */
    function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Extract content from string.
     *
     * @param  string $content
     * @return Document
     */
    abstract public function extract($content);

    /**
     * Load content from file.
     *
     * @param  string   $filename
     * @return Document
     */
    abstract public function load($filename);
}