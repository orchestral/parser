<?php namespace Orchestra\Parser\TestCase;

use Mockery as m;
use Orchestra\Parser\XmlServiceProvider;

class XmlServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceProviderIsDeferred()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }
}
