<?php

namespace Workbench\App;

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
