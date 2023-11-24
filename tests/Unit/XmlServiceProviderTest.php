<?php

namespace Orchestra\Parser\Tests\Unit;

use Orchestra\Parser\XmlServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class XmlServiceProviderTest extends TestCase
{
    #[Test]
    public function it_deferred_the_service_registration()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertTrue($stub->isDeferred());
    }

    #[Test]
    public function it_provides_expected_services()
    {
        $stub = new XmlServiceProvider(null);

        $this->assertContains('orchestra.parser.xml', $stub->provides());
    }
}
