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
                    'users' => ['uses' => 'user[id,name>fullname]'],
                ],
                [
                    'users' => [
                        [
                            'id'   => '1',
                            'fullname' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id'   => '2',
                            'fullname' => 'Taylor Otwell',
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
        <tag>Laravel</tag>
        <tag>PHP</tag>
    </user>
</api>',
                [
                    'users' => ['uses' => 'user[id,name>fullname,tag(!=@)]'],
                ],
                [
                    'users' => [
                        [
                            'id'   => '1',
                            'fullname' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id'   => '2',
                            'fullname' => 'Taylor Otwell',
                            'tag' => ['Laravel', 'PHP']
                        ],
                    ],
                ],
            ],
            [
'<api>
    <user>
        <property id="id">
            <value>1</value>
        </property>
        <property id="name">
            <value>Mior Muhammad Zaki</value>
        </property>
    </user>
    <user>
        <property id="id">
            <value>2</value>
        </property>
        <property id="name">
            <value>Taylor Otwell</value>
        </property>
    </user>
</api>',
                [
                    'users' => ['uses' => 'user[property(::id=value)]'],
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
        <property id="id">1</property>
        <property id="name">Mior Muhammad Zaki</property>
    </user>
    <user>
        <property id="id">2</property>
        <property id="name">Taylor Otwell</property>
    </user>
</api>',
                [
                    'users' => ['uses' => 'user[property(::id=@)]'],
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
            [
'<products>
    <product ID="123456">
        <name>Lord of the Rings</name>
        <description>Just a book.</description>
        <properties>
            <property name="id">
                <value>2108</value>
            </property>
            <property name="avail">
                <value>1</value>
            </property>
            <property name="cat">
                <value>Fantasy Books</value>
            </property>
        </properties>
    </product>
    <product ID="123457">
        <name>Winnie The Pooh</name>
        <description>Good for children.</description>
        <properties>
            <property name="id">
                <value>3763</value>
            </property>
            <property name="avail">
                <value>0</value>
            </property>
            <property name="cat">
                <value>Child Books</value>
            </property>
        </properties>
    </product>
</products>',
                [
                    'books' => ['uses' => 'product[::ID>id,name,properties.property(::name=value)>meta]', 'default' => null]
                ],
                [
                    'books' => [
                        [
                            'id' => '123456',
                            'name' => 'Lord of the Rings',
                            'meta' => [
                                'id' => '2108',
                                'avail' => '1',
                                'cat' => 'Fantasy Books',
                            ],
                        ],
                        [
                            'id' => '123457',
                            'name' => 'Winnie The Pooh',
                            'meta' => [
                                'id' => '3763',
                                'avail' => '0',
                                'cat' => 'Child Books',
                            ],
                        ],
                    ]
                ]
            ],
            [
'<products>
    <product ID="123456">
        <name>Lord of the Rings</name>
        <description>Just a book.</description>
        <properties>
            <property name="id">
                <value>2108</value>
            </property>
            <property name="avail">
                <value>1</value>
            </property>
            <property name="cat">
                <value>Fantasy Books</value>
            </property>
        </properties>
    </product>
    <product ID="123457">
        <name>Winnie The Pooh</name>
        <description>Good for children.</description>
        <properties>
            <property name="id">
                <value>3763</value>
            </property>
            <property name="avail">
                <value>0</value>
            </property>
            <property name="cat">
                <value>Child Books</value>
            </property>
        </properties>
    </product>
</products>',
                [
                    'books' => ['uses' => 'product[::ID>bookID,name,properties.property(::name=value)]', 'default' => null]
                ],
                [
                    'books' => [
                        [
                            'bookID' => '123456',
                            'name' => 'Lord of the Rings',
                            'id' => '2108',
                            'avail' => '1',
                            'cat' => 'Fantasy Books',
                        ],
                        [
                            'bookID' => '123457',
                            'name' => 'Winnie The Pooh',
                            'id' => '3763',
                            'avail' => '0',
                            'cat' => 'Child Books',
                        ],
                    ]
                ]
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
