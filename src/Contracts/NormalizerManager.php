<?php

namespace Curlyspoon\Cms\Contracts;

use Closure;

interface NormalizerManager
{
    public function register(string $name, Closure $normalizer): self;

    public function normalizer(string $name): Closure;

    public function getNormalizers(): array;
}
