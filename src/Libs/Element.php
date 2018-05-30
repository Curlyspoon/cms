<?php

namespace Curlyspoon\Cms\Libs;

use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerContract;
use Curlyspoon\Core\Elements\BladeElement;
use Illuminate\Container\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class Element extends BladeElement
{
    /**
     * @var array
     *
     * @see NormalizerManagerContract::normalizer()
     * @see OptionsResolver::setNormalizer()
     */
    protected $normalizers = [];

    protected function getPath(): string
    {
        return Container::getInstance()->make('config')->get('curlyspoon.element_view_prefix');
    }

    protected function optionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->defaults);

        $resolver->setRequired($this->required);

        foreach ($this->types as $option => $types) {
            $resolver->setAllowedTypes($option, $types);
        }

        foreach ($this->values as $option => $values) {
            $resolver->setAllowedValues($option, $values);
        }

        foreach ($this->normalizers as $option => $normalizer) {
            $resolver->setNormalizer($option, Container::getInstance()->make(NormalizerManagerContract::class)->normalize($normalizer));
        }

        if (method_exists($this, 'configureOptions')) {
            $this->configureOptions($resolver);
        }

        return $resolver;

    }
}
