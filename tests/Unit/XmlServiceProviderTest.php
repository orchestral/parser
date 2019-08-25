<?php

namespace Orchestra\Parser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Orchestra\Parser\XmlServiceProvider;

class XmlServiceProviderTest extends TestCase
{
    /** @test */
    public function it_deferred_the_service_registration()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }

    /** @test */
    public function it_provides_expected_services()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertContains('orchestra.parser.xml', $stub->provides());
    }
}
