<?php

namespace Curlyspoon\Cms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Curlyspoon\Core\Managers\ElementManager;

class CurlyspoonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('curlyspoon.manager.element', function (): ElementManager {
            return new ElementManager();
        });

        $this->registerBladeDirective();
    }

    protected function registerBladeDirective()
    {
        Blade::directive('element', function (string $name, array $options = []) {
            return "<?php echo app('curlyspoon.manager.element')->render($name, $options); ?>";
        });
    }
}
