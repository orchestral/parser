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
        $stub = new Document(new Container);

        $content = '<foo><bar hello="hello world">foobar</bar><world></world></foo>';
        $schema  = [
            'foo'      => ['uses' => 'bar'],
            'hello'    => ['uses' => 'bar::hello'],
            'world'    => ['uses' => 'world', 'default' => false],
            'foobar'   => ['uses' => 'bar::foobar', 'default' => false],
            'username' => ['uses' => 'user::name', 'default' => 'Guest'],
        ];

        $expected = [
            'foo'      => 'foobar',
            'hello'    => 'hello world',
            'world'    => false,
            'foobar'   => false,
            'username' => 'Guest',
        ];

        $stub->setContent(simplexml_load_string($content));

        $data = $stub->parse($schema);

        $this->assertEquals($expected, $data);
    }
}
