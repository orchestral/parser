<?php namespace Orchestra\Parser\Xml\TestCase;

use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Parser\Xml\Reader::extract() method.
     *
     * @test
     */
    public function testExtractMethod()
    {
        $xml = '<xml><foo>foobar</foo></xml>';

        $app = new Container;
        $document = new Document($app);
        $stub = new Reader($document);
        $output = $stub->extract($xml);

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    /**
     * Test Orchestra\Parser\Xml\Reader::load() method.
     *
     * @test
     */
    public function testLoadMethod()
    {
        $app = new Container;
        $document = new Document($app);
        $stub = new Reader($document);
        $output = $stub->load(__DIR__.'/stub/foo.xml');

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    /**
     * Test Orchestra\Parser\Xml\Reader::extract() method throws exception.
     *
     * @expectedException \Orchestra\Parser\InvalidContentException
     */
    public function testExtractMethodThrowsException()
    {
        $xml = '<xml><foo>foobar<foo></xml>';

        $app = new Container;
        $document = new Document($app);
        $stub = new Reader($document);
        $output = $stub->extract($xml);
    }
}
