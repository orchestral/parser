<?php

namespace Orchestra\Parser\Tests\Feature\Stubs;

class Filter
{
    public function filterStrToUpper($value)
    {
        return strtoupper($value);
    }

    public function filterStrToLower($value)
    {
        return strtolower($value);
    }
}
