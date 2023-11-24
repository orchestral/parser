<?php

namespace Orchestra\Parser\Tests\Unit;

use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    #[Test]
    public function it_can_extract_valid_xml_from_string()
    {
        $xml = '<xml><foo>foobar</foo></xml>';

        $app = new Container();
        $document = new Document($app);
        $stub = new Reader($document);
        $output = $stub->extract($xml);

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    #[Test]
    public function it_can_load_valid_xml_from_filesystem()
    {
        $stub = new Reader(new Document(new Container()));
        $output = $stub->load(__DIR__.'/fixtures/foo.xml');

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Document', $output);
    }

    #[Test]
    public function it_throws_exception_when_given_invalid_xml_content()
    {
        $this->expectException('Laravie\Parser\InvalidContentException');

        $xml = '<xml><foo>foobar<foo></xml>';

        $stub = new Reader(new Document(new Container()));
        $output = $stub->extract($xml);
    }
}
