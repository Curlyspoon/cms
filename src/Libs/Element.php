<?php

namespace Curlyspoon\Cms\Libs;

use Curlyspoon\Core\Elements\BladeElement;
use Illuminate\Container\Container;

abstract class Element extends BladeElement
{
    protected function getPath(): string
    {
        return Container::getInstance()->make('config')->get('curlyspoon.element_view_prefix');
    }
}
