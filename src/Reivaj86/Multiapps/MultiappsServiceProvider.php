<?php

namespace Reivaj86\Multiapps;

use Illuminate\Support\ServiceProvider;

class MultiappsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/multiapps.php' => config_path('multiapps.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../migrations/' => base_path('/database/migrations')
        ], 'migrations');

        $this->registerBladeExtensions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/multiapps.php', 'appls');
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createMatcher('appl');

            return preg_replace($pattern, '<?php if (Auth::check() && Auth::user()->uses$2): ?> ', $view);
        });

        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createPlainMatcher('endappl');

            return preg_replace($pattern, '<?php endif; ?>', $view);
        });

        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createMatcher('allowedappl');

            return preg_replace($pattern, '<?php if (Auth::check() && Auth::user()->allowed$2): ?> ', $view);
        });

        $blade->extend(function ($view, $compiler) {
            $pattern = $compiler->createPlainMatcher('endallowedappl');

            return preg_replace($pattern, '<?php endif; ?>', $view);
        });
    }
}
