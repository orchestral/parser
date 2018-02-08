<?php

namespace Orchestra\Parser\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Parser\XmlServiceProvider;

class XmlServiceProviderTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_deferred_the_service_registration()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }

    /** @test */
    public function it_register_expected_services()
    {
        $app = new Container();

        $stub = new XmlServiceProvider($app);
        $stub->register();

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Reader', $app['orchestra.parser.xml']);
    }

    /** @test */
    public function it_provides_expected_services()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertContains('orchestra.parser.xml', $stub->provides());
    }
}
