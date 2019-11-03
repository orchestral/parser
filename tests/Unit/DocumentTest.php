<?php

namespace Orchestra\Parser\Tests\Unit;

use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataCollectionProvider
     */
    public function it_can_parse_given_xml($content, $schema, $expected)
    {
        $stub = new class(new Container()) extends Document {
            public function filterStrToUpper($value)
            {
                return strtoupper($value);
            }
        };

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
                    'foo' => ['uses' => 'bar', 'filter' => '@strToUpper'],
                    'hello' => ['uses' => ['bar::hello', 'bar'], 'filter' => '@notFilterable'],
                    'world' => ['uses' => 'world', 'default' => false],
                    'foobar' => ['uses' => 'bar::foobar', 'default' => false],
                    'username' => ['uses' => 'user::name', 'default' => 'Guest', 'filter' => FilterStub::class.'@filterStrToLower'],
                    'google' => 'google.com',
                    'facebook' => ['default' => 'facebook.com'],
                ],
                [
                    'foo' => 'FOOBAR',
                    'hello' => ['hello world', 'foobar'],
                    'world' => false,
                    'foobar' => false,
                    'username' => 'guest',
                    'google' => 'google.com',
                    'facebook' => 'facebook.com',
                ],
            ],
        ];
    }
}

class FilterStub
{
    public function filterStrToLower($value)
    {
        return strtolower($value);
    }
}
