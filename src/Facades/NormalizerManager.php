<?php

namespace Curlyspoon\Cms\Facades;

use Illuminate\Support\Facades\Facade;
use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerConract;

class NormalizerManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return NormalizerManagerConract::class;
    }
}
