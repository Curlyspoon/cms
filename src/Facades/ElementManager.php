<?php

namespace Curlyspoon\Cms\Facades;

use Illuminate\Support\Facades\Facade;
use Curlyspoon\Core\Contracts\ElementManager as ElementManagerContract;

class ElementManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ElementManagerContract::class;
    }
}
