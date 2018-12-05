<?php

namespace Curlyspoon\Cms\Libs;

use Illuminate\Container\Container;
use Curlyspoon\Core\Elements\BladeElement;
use Curlyspoon\NestedOptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolver as SymfonyOptionsResolver;

abstract class Element extends BladeElement
{
    /**
     * @var array
     *
     * @see NormalizerManagerContract::normalizer()
     * @see OptionsResolver::setNormalizer()
     */
    protected $normalizers = [];

    /**
     * @var array
     *
     * @see OptionsResolver::setNested()
     */
    protected $nested = [];

    protected function getPath(): string
    {
        return Container::getInstance()->make('config')->get('curlyspoon.element_view_prefix');
    }

    protected function optionsResolver(): SymfonyOptionsResolver
    {
        $resolver = OptionsResolver::make([
            'defaults' => $this->defaults,
            'required' => $this->required,
            'types' => $this->types,
            'values' => $this->values,
            'normalizers' => $this->normalizers,
            'nested' => $this->nested,
        ]);

        if (method_exists($this, 'configureOptions')) {
            $this->configureOptions($resolver);
        }

        return $resolver;
    }
}
