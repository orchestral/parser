<?php

namespace Orchestra\Parser\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

class XmlServiceProviderTest extends TestCase
{
    #[Test]
    public function it_register_expected_services()
    {
        $this->assertInstanceOf('\Orchestra\Parser\Xml\Reader', $this->app['orchestra.parser.xml']);
    }
}
