<?php

namespace Curlyspoon\Cms\Libs;

use Illuminate\Container\Container;
use Curlyspoon\Core\Elements\BladeElement;

abstract class Element extends BladeElement
{
    protected function getPath(): string
    {
        return Container::getInstance()->make('config')->get('curlyspoon.element_view_prefix');
    }
}
