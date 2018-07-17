<?php

namespace Curlyspoon\Cms\Libs;

use Illuminate\Container\Container;
use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;
use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerContract;

class OptionsResolver extends SymfonyOptionsResolver
{
    protected $nested = [];

    public static function make(array $config = []): OptionsResolver
    {
        $resolver = new static();

        if(!empty($config['defaults'])) {
            $resolver->setDefaults($config['defaults']);
        }

        if(!empty($config['required'])) {
            $resolver->setRequired($config['required']);
        }

        if(!empty($config['types'])) {
            foreach ($config['types'] as $option => $types) {
                $resolver->setAllowedTypes($option, $types);
            }
        }

        if(!empty($config['values'])) {
            foreach ($config['values'] as $option => $values) {
                $resolver->setAllowedValues($option, $values);
            }
        }

        if(!empty($config['normalizers'])) {
            foreach ($config['normalizers'] as $option => $normalizer) {
                $resolver->setNormalizer($option, Container::getInstance()->make(NormalizerManagerContract::class)->normalizer($normalizer));
            }
        }

        if(!empty($config['nested'])) {
            foreach ($config['nested'] as $option => $nestedConfig) {
                $resolver->setNested($option, static::make($nestedConfig));
            }
        }

        return $resolver;
    }

    public function setNested(string $key, OptionsResolver $resolver): OptionsResolver
    {
        $this->nested[$key] = $resolver;

        return $this;
    }

    public function isNested(): bool
    {
        return !empty($this->nested);
    }

    public function resolve(array $options = array())
    {
        $resolved = parent::resolve($options);

        foreach ($this->nested as $key => $resolver) {
            $isLoop = ends_with($key, '.*');
            if ($isLoop) {
                $key = substr($key, 0, -2);
            }
            $dataToResolve = array_get($resolved, $key);
            if (!$isLoop) {
                array_set($resolved, $key, $resolver->resolve($dataToResolve));
                continue;
            }

            foreach ($dataToResolve as $subKey => $data) {
                array_set($resolved, $key . '.' . $subKey, $resolver->resolve($data));
            }
        }

        return $resolved;
    }
}
