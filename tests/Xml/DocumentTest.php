<?php namespace Orchestra\Parser\TestCase\Xml;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

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


}
