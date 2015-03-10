<?php namespace Orchestra\Parser\TestCase\Xml;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Parser\Xml\Document::setContent() method.
     *
     * @test
     */
    public function testSetContentMethod()
    {
        $expected = '<foo><bar>foobar</bar></foo>';

        $stub = new Document(new Container());

        $stub->setContent($expected);

        $refl    = new \ReflectionObject($stub);
        $content = $refl->getProperty('content');
        $content->setAccessible(true);

        $this->assertEquals($expected, $content->getValue($stub));
    }

    /**
     * Test Orchestra\Parser\Xml\Document::getContent() method.
     *
     * @test
     */
    public function testGetContentMethod()
    {
        $expected = '<foo><bar>foobar</bar></foo>';

        $stub = new Document(new Container());

        $refl    = new \ReflectionObject($stub);
        $content = $refl->getProperty('content');
        $content->setAccessible(true);

        $content->setValue($stub, $expected);

        $this->assertEquals($expected, $stub->getContent());
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
            [
'<api>
    <collection>
        <user>
            <id>1</id>
            <name>Mior Muhammad Zaki</name>
        </user>
        <user>
            <id>2</id>
            <name>Taylor Otwell</name>
        </user>
    </collection>
</api>',
                [
                    'users' => ['uses' => 'collection.user[id,name]'],
                ],
                [
                    'users' => [
                        [
                            'id'   => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id'   => '2',
                            'name' => 'Taylor Otwell',
                        ],
                    ],
                ],
            ],
            [
'<api>
    <user>
        <id>1</id>
        <name>Mior Muhammad Zaki</name>
    </user>
    <user>
        <id>2</id>
        <name>Taylor Otwell</name>
    </user>
</api>',
                [
                    'users' => ['uses' => 'user[id,name]'],
                ],
                [
                    'users' => [
                        [
                            'id'   => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id'   => '2',
                            'name' => 'Taylor Otwell',
                        ],
                    ],
                ],
            ],
            [
'<api></api>',
                [
                    'users' => ['uses' => 'user[id,name]', 'default' => null],
                ],
                [
                    'users' => null,
                ],
            ],
            [
'<api><user></user></api>',
                [
                    'users' => ['uses' => 'user[id,name]', 'default' => null],
                ],
                [
                    'users' => [],
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
