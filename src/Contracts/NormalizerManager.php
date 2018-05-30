<?php

namespace Curlyspoon\Cms\Contracts;

use Closure;

interface NormalizerManager
{
    public function register(string $name, Closure $normalizer): self;

    public function normalize(string $name): Closure;

    public function getNormalizers(): array;
}
