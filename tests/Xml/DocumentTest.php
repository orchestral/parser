<?php

namespace Orchestra\Parser\TestCase\Xml;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Parser\Xml\Document;
use Illuminate\Container\Container;

class DocumentTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Parser\Xml\Document::parse() method.
     *
     * @test
     * @dataProvider dataCollectionProvider
     */
    public function testParseMethod($content, $schema, $expected)
    {
        $stub = new DocumentStub(new Container());

        $stub->setContent(simplexml_load_string($content));

        $data = $stub->parse($schema);

        $this->assertEquals($expected, $data);
    }

    public function dataCollectionProvider()
    {
        return [
            [
'<foo>
    <bar hello="hello world">foobar</bar>
    <world></world>
</foo>',
                [
                    'foo'      => ['uses' => 'bar', 'filter' => '@strToUpper'],
                    'hello'    => ['uses' => ['bar::hello', 'bar'], 'filter' => '@notFilterable'],
                    'world'    => ['uses' => 'world', 'default' => false],
                    'foobar'   => ['uses' => 'bar::foobar', 'default' => false],
                    'username' => ['uses' => 'user::name', 'default' => 'Guest', 'filter' => '\Orchestra\Parser\TestCase\Xml\FilterStub@filterStrToLower'],
                    'google'   => 'google.com',
                    'facebook' => ['default' => 'facebook.com'],
                ],
                [
                    'foo'      => 'FOOBAR',
                    'hello'    => ['hello world', 'foobar'],
                    'world'    => false,
                    'foobar'   => false,
                    'username' => 'guest',
                    'google'   => 'google.com',
                    'facebook' => 'facebook.com',
                ],
            ],
        ];
    }
}

class DocumentStub extends \Orchestra\Parser\Xml\Document
{
    public function filterStrToUpper($value)
    {
        return strtoupper($value);
    }
}

class FilterStub
{
    public function filterStrToLower($value)
    {
        return strtolower($value);
    }
}
