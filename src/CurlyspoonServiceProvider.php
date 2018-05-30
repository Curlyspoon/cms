<?php

namespace Curlyspoon\Cms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Curlyspoon\Core\Managers\ElementManager;
use ReflectionClass;

class CurlyspoonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/curlyspoon.php'), 'curlyspoon');

        $this->app->singleton('curlyspoon.manager.element', function (): ElementManager {
            return new ElementManager();
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
            return "<?php echo app('curlyspoon.manager.element')->render($expression); ?>";
        });
    }

    protected function loadElements()
    {
        if(!$this->app['config']->get('curlyspoon.autoload')) {
            return;
        }

        $namespace = $this->app['config']->get('curlyspoon.autoload.namespace');

        foreach (glob($this->app['config']->get('curlyspoon.autoload.directory') . '/*.php') as $filename) {
            $classname = $namespace . '\\' . pathinfo($filename, PATHINFO_FILENAME);

            if (class_exists($classname)) {
                $reflection = new ReflectionClass($classname);

                if (!$reflection->isAbstract() && $reflection->hasProperty('name')) {
                    $this->app->make('curlyspoon.manager.element')->register($reflection->getDefaultProperties()['name'], $classname);
                }
            }
        }
    }
}
