<?php namespace Orchestra\Parser\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Parser\XmlServiceProvider;

class XmlServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Parser\XmlServiceProvider::$defer property.
     *
     * @test
     */
    public function testServiceProviderIsDeferred()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }

    public function testRegisterMethod()
    {
        $app = new Container;

        $stub = new XmlServiceProvider($app);
        $stub->register();

        $this->assertInstanceOf('\Orchestra\Parser\Xml\Reader', $app['orchestra.parser.xml']);
    }

    public function testProvidesMethod()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertContains('orchestra.parser.xml', $stub->provides());
    }
}
