<?php

namespace Curlyspoon\Cms\Managers;

use Closure;
use InvalidArgumentException;
use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerContract;

class NormalizerManager implements NormalizerManagerContract
{
    protected $normalizers = [];

    public function register(string $name, Closure $normalizer): self
    {
        $this->normalizers[$name] = $normalizer;

        return $this;
    }

    public function normalize(string $name): Closure
    {
        if (! isset($this->normalizers[$name])) {
            throw new InvalidArgumentException(sprintf('No normalizer with name [%s] found.', $name));
        }

        return $this->normalizers[$name];
    }

    public function getNormalizers(): array
    {
        return $this->normalizers;
    }
}
