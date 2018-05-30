<?php

namespace Curlyspoon\Cms\Libs;

use Curlyspoon\Core\Elements\BladeElement;

abstract class Element extends BladeElement
{
    protected function getPath(): string
    {
        return 'elements';
    }
}
