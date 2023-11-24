<?php

namespace Orchestra\Parser\Tests\Feature;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    use WithWorkbench;

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'XmlParser' => \Orchestra\Parser\Xml\Facade::class,
        ];
    }
}
