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
     * @dataProvider dataCollectionProvider
     */
    public function testParseMethod($content, $schema, $expected)
    {
        $stub = new DocumentStub(new Container);

        $stub->setContent(simplexml_load_string($content));

        $data = $stub->parse($schema);

        $this->assertEquals($expected, $data);
    }

    public function dataCollectionProvider()
    {
        return array(
            array(
'<foo>
    <bar hello="hello world">foobar</bar>
    <world></world>
</foo>',
                array(
                    'foo'      => array('uses' => 'bar', 'filter' => '@strToUpper'),
                    'hello'    => array('uses' => array('bar::hello', 'bar'), 'filter' => '@notFilterable'),
                    'world'    => array('uses' => 'world', 'default' => false),
                    'foobar'   => array('uses' => 'bar::foobar', 'default' => false),
                    'username' => array('uses' => 'user::name', 'default' => 'Guest', 'filter' => '\Orchestra\Parser\TestCase\Xml\FilterStub@filterStrToLower'),
                    'google'   => 'google.com',
                    'facebook' => array('default' => 'facebook.com'),
                ),
                array(
                    'foo'      => 'FOOBAR',
                    'hello'    => array('hello world', 'foobar'),
                    'world'    => false,
                    'foobar'   => false,
                    'username' => 'guest',
                    'google'   => 'google.com',
                    'facebook' => 'facebook.com',
                ),
            ),
            array(
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
                array(
                    'users' => array('uses' => 'collection.user[id,name]'),
                ),
                array(
                    'users' => array(
                        array(
                            'id'   => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ),
                        array(
                            'id'   => '2',
                            'name' => 'Taylor Otwell',
                        ),
                    ),
                ),
            ),
            array(
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
                array(
                    'users' => array('uses' => 'user[id,name]'),
                ),
                array(
                    'users' => array(
                        array(
                            'id'   => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ),
                        array(
                            'id'   => '2',
                            'name' => 'Taylor Otwell',
                        ),
                    ),
                ),
            ),
            array(
'<api></api>',
                array(
                    'users' => array('uses' => 'user[id,name]', 'default' => null),
                ),
                array(
                    'users' => null,
                ),
            ),
            array(
'<api><user></user></api>',
                array(
                    'users' => array('uses' => 'user[id,name]', 'default' => null),
                ),
                array(
                    'users' => array(),
                ),
            ),
        );

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
