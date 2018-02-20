<?php

namespace Orchestra\Parser\TestCase\Feature;

class XmlServiceProviderTest extends TestCase
{
    /** @test */
    public function it_register_expected_services()
    {
        $this->assertInstanceOf('\Orchestra\Parser\Xml\Reader', $this->app['orchestra.parser.xml']);
    }
}
