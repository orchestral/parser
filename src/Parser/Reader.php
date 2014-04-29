<?php namespace Orchestra\Parser;


abstract class Reader
{
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