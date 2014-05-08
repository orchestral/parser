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

        $stub = new Document(new Container);

        $stub->setContent($expected);

        $refl = new \ReflectionObject($stub);
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

        $stub = new Document(new Container);

        $refl = new \ReflectionObject($stub);
        $content = $refl->getProperty('content');
        $content->setAccessible(true);

        $content->setValue($stub, $expected);

        $this->assertEquals($expected, $stub->getContent());
    }

    /**
     * Test Orchestra\Parser\Xml\Document::parse() method.
     *
     * @test
     */
    public function testParseMethod()
    {
        $stub = new DocumentStub(new Container);

        $content = '<foo><bar hello="hello world">foobar</bar><world></world></foo>';
        $schema  = [
            'foo'      => ['uses' => 'bar', 'filter' => '@strToUpper'],
            'hello'    => ['uses' => ['bar::hello', 'bar'], 'filter' => '@notFilterable'],
            'world'    => ['uses' => 'world', 'default' => false],
            'foobar'   => ['uses' => 'bar::foobar', 'default' => false],
            'username' => ['uses' => 'user::name', 'default' => 'Guest', 'filter' => '\Orchestra\Parser\TestCase\Xml\FilterStub@filterStrToLower'],
            'google'   => 'google.com',
            'facebook' => ['default' => 'facebook.com'],
        ];

        $expected = [
            'foo'      => 'FOOBAR',
            'hello'    => ['hello world', 'foobar'],
            'world'    => false,
            'foobar'   => false,
            'username' => 'guest',
            'google'   => 'google.com',
            'facebook' => 'facebook.com',
        ];

        $stub->setContent(simplexml_load_string($content));

        $data = $stub->parse($schema);

        $this->assertEquals($expected, $data);
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
