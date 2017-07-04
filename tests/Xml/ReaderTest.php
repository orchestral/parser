<?php

namespace Orchestra\Parser\Xml\TestCase;

use PHPUnit\Framework\TestCase;
use Orchestra\Parser\Xml\Reader;
use Orchestra\Parser\Xml\Document;
use Illuminate\Container\Container;

class ReaderTest extends TestCase
{
    /**
     * Test Orchestra\Parser\Xml\Reader::extract() method.
     *
     * @test
     */
    public function testExtractMethod()
    {
        $xml = '<xml><foo>foobar</foo></xml>';

        $app      = new Container();
        $document = new Document($app);
        $stub     = new Reader($document);
        $output   = $stub->extract($xml);

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    /**
     * Test Orchestra\Parser\Xml\Reader::load() method.
     *
     * @test
     */
    public function testLoadMethod()
    {
        $app      = new Container();
        $document = new Document($app);
        $stub     = new Reader($document);
        $output   = $stub->load(__DIR__.'/stub/foo.xml');

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    /**
     * Test Orchestra\Parser\Xml\Reader::extract() method throws exception.
     *
     * @expectedException \Laravie\Parser\InvalidContentException
     */
    public function testExtractMethodThrowsException()
    {
        $xml = '<xml><foo>foobar<foo></xml>';

        $app      = new Container();
        $document = new Document($app);
        $stub     = new Reader($document);
        $output   = $stub->extract($xml);
    }
}
