<?php

namespace BladeUI\BladeMaterialSymbols;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeMaterialSymbolsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-material-symbols', []);
            $factory->add('material-symbols', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-material-symbols.php', 'blade-material-symbols');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/blade-material-symbols'),
            ], 'blade-material-symbols');

            $this->publishes([
                __DIR__ . '/../config/blade-material-symbols.php' => $this->app->configPath('blade-material-symbols.php'),
            ], 'blade-material-symbols-config');
        }
    }
}
