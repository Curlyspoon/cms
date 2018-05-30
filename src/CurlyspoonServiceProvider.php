<?php

namespace Curlyspoon\Cms;

use ReflectionClass;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Curlyspoon\Core\Managers\ElementManager;
use Curlyspoon\Cms\Managers\NormalizerManager;
use Curlyspoon\Core\Contracts\ElementManager as ElementManagerContract;
use Curlyspoon\Cms\Contracts\NormalizerManager as NormalizerManagerConract;

class CurlyspoonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/curlyspoon.php'), 'curlyspoon');

        $this->app->singleton(ElementManagerContract::class, function (): ElementManager {
            return new ElementManager();
        });

        $this->app->singleton(NormalizerManagerConract::class, function (): NormalizerManager {
            return new NormalizerManager();
        });

        $this->registerBladeDirective();
    }

    public function boot()
    {
        $this->loadElements();
    }

    protected function registerBladeDirective()
    {
        Blade::directive('element', function ($expression) {
            return "<?php echo app(\Curlyspoon\Cms\Contracts\ElementManager::class)->render($expression); ?>";
        });
    }

    protected function loadElements()
    {
        if (! $this->app['config']->get('curlyspoon.autoload')) {
            return;
        }

        $namespace = $this->app['config']->get('curlyspoon.autoload.namespace');

        foreach (glob($this->app['config']->get('curlyspoon.autoload.directory').'/*.php') as $filename) {
            $classname = $namespace.'\\'.pathinfo($filename, PATHINFO_FILENAME);

            if (class_exists($classname)) {
                $reflection = new ReflectionClass($classname);

                if (! $reflection->isAbstract() && $reflection->hasProperty('name')) {
                    $this->app->make(ElementManagerContract::class)->register($reflection->getDefaultProperties()['name'], $classname);
                }
            }
        }
    }
}
