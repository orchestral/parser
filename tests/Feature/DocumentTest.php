<?php

namespace Orchestra\Parser\Tests\Feature;

use Orchestra\Parser\Xml\Facade as XmlParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Workbench\App\Filter;

class DocumentTest extends TestCase
{
    #[Test]
    public function it_can_be_rebased()
    {
        $expected = '<foo><bar>foobar</bar></foo>';

        $document = XmlParser::extract($expected)->rebase();

        $this->assertEquals(simplexml_load_string($expected), $document->getContent());
    }

    #[Test]
    public function it_can_use_namespaced()
    {
        $document = XmlParser::via(simplexml_load_string(
            '<?xml version="1.0" standalone="yes"?>
                <people xmlns:p="http://example.org/ns" xmlns:t="http://example.org/test">
                    <p:person id="1">JohnDoe</p:person>
                    <p:person id="2">@Susie Q. Public</p:person>
                </people>'
        ));

        $result = $document->namespaced('p', [], []);

        $this->assertCount(0, $result);
    }

    #[Test]
    #[DataProvider('dataCollectionProvider')]
    public function it_can_parse($content, $schema, $expected)
    {
        $document = XmlParser::via(simplexml_load_string($content));

        $data = $document->parse($schema);

        $this->assertEquals($expected, $data);
    }

    #[Test]
    public function it_can_parse_with_tags()
    {
        $expected = [
            'users' => [
                [
                    'id' => '1',
                    'fullname' => 'Mior Muhammad Zaki',
                ],
                [
                    'id' => '2',
                    'fullname' => 'Taylor Otwell',
                    'tag' => ['Laravel', 'PHP'],
                ],
            ],
        ];

        $document = XmlParser::via(simplexml_load_string('<api>
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
</api>'));

        $data = $document->parse([
            'users' => ['uses' => 'user[id,name>fullname,tag(@=@)]'],
        ]);

        $this->assertEquals($expected, $data);
    }

    public static function dataCollectionProvider()
    {
        return [
            [
                '<api>
    <user followers="5">
        <id>1</id>
        <email type="primary">crynobone@gmail.com</email>
    </user>
</api>',
                [
                    'id' => ['uses' => 'user.id'],
                    'email' => ['uses' => 'user.email'],
                    'followers' => ['uses' => 'user::followers'],
                    'email_type' => ['uses' => 'user.email::type'],
                ],
                [
                    'id' => 1,
                    'email' => 'crynobone@gmail.com',
                    'followers' => 5,
                    'email_type' => 'primary',
                ],
            ],
            [
                '<foo>
    <bar hello="hello world">foobar</bar>
    <world></world>
</foo>',
                [
                    'foo' => ['uses' => 'bar', 'filter' => Filter::class.'@filterStrToUpper'],
                    'hello' => ['uses' => ['bar::hello', 'bar'], 'filter' => '@notFilterable'],
                    'world' => ['uses' => 'world', 'default' => false],
                    'foobar' => ['uses' => 'bar::foobar', 'default' => false],
                    'username' => ['uses' => 'user::name', 'default' => 'Guest', 'filter' => Filter::class.'@filterStrToLower'],
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
                            'id' => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id' => '2',
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
                            'id' => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id' => '2',
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
                            'id' => '1',
                            'fullname' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id' => '2',
                            'fullname' => 'Taylor Otwell',
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
                            'id' => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id' => '2',
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
                            'id' => '1',
                            'name' => 'Mior Muhammad Zaki',
                        ],
                        [
                            'id' => '2',
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
                    'books' => ['uses' => 'product[::ID>id,name,properties.property(::name=value)>meta]', 'default' => null],
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
                    ],
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
                    'books' => ['uses' => 'product[::ID>bookID,name,properties.property(::name=value)]', 'default' => null],
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
                    ],
                ],
            ],
            [
                '<api>
    <Country name="Albania" id="ALB">
        <Competition id="ALB_1" name="Albania 1" event_name="Super League" sport="soccer" levels_on_pyramid="0" competition_type="league" image="" timestamp="0"/>
    </Country>
    <Country name="Algeria" id="ALG">
        <Competition id="ALG_1" name="Algeria 1" event_name="Ligue 1" sport="soccer" levels_on_pyramid="0" competition_type="league" image="" timestamp="0"/>
    </Country>
</api>',
                [
                    'data' => ['uses' => 'Country[Competition::id>id,Competition::name>name,Competition::event_name>event_name]', 'default' => null],
                ],
                [
                    'data' => [
                        [
                            'id' => 'ALB_1',
                            'name' => 'Albania 1',
                            'event_name' => 'Super League',
                        ],
                        [
                            'id' => 'ALG_1',
                            'name' => 'Algeria 1',
                            'event_name' => 'Ligue 1',
                        ],
                    ],
                ],
            ],
            [
                '<xml time="1460026675">
    <Country id="ALG" name="Algeria" image="Algeria.png" lastupdate="1315773004"/>
    <Country id="ASM" name="American Samoa" image="American-Samoa.png" lastupdate="1315773004"/>
    <Country id="AND" name="Andorra" image="Andorra.png" lastupdate="1315773004"/>
</xml>',
                [
                    'countries' => ['uses' => 'Country[::id>id,::name>name]', 'default' => null],
                ],
                [
                    'countries' => [
                        [
                            'id' => 'ALG',
                            'name' => 'Algeria',
                        ],
                        [
                            'id' => 'ASM',
                            'name' => 'American Samoa',
                        ],
                        [
                            'id' => 'AND',
                            'name' => 'Andorra',
                        ],
                    ],
                ],
            ],
        ];
    }
}
