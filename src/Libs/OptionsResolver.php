<?php

namespace Curlyspoon\Cms\Libs;

use Illuminate\Support\Arr;
use Illuminate\Container\Container;
use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerContract;
use Curlyspoon\NestedOptionsResolver\OptionsResolver as CurlyspoonOptionsResolver;

class OptionsResolver extends CurlyspoonOptionsResolver
{
    public static function make(array $config = []): CurlyspoonOptionsResolver
    {
        $resolver = new static();

        $resolver->setDefined(array_merge(
            Arr::get($config, 'defaults', []),
            Arr::get($config, 'required', []),
            array_keys(Arr::get($config, 'types', [])),
            array_keys(Arr::get($config, 'values', [])),
            array_keys(Arr::get($config, 'normalizers', []))
        ));

        $resolver
            ->loadConfigDefaults($config)
            ->loadConfigRequired($config)
            ->loadConfigTypes($config)
            ->loadConfigValues($config)
            ->loadConfigNormalizers($config)
            ->loadConfigNested($config);

        return $resolver;
    }

    protected function loadConfigNormalizers(array $config): OptionsResolver
    {
        if (! empty($config['normalizers'])) {
            foreach ($config['normalizers'] as $option => $normalizer) {
                $this->setNormalizer($option, Container::getInstance()->make(NormalizerManagerContract::class)->normalizer($normalizer));
            }
        }

        return $this;
    }
}
