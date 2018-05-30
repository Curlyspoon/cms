<?php

namespace Curlyspoon\Cms\Facades;

use Illuminate\Support\Facades\Facade;

class ElementManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'curlyspoon.manager.element';
    }
}
