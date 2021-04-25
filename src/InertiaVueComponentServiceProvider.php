<?php

namespace Pkboom\InertiaVueComponent;

use Illuminate\Support\ServiceProvider;
use Pkboom\InertiaVueComponent\Commands\MakeInertiaVueComponent;

class InertiaVueComponentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../stubs/vue-component.stub' => base_path('stubs/vue-component.stub'),
        ], 'stubs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeInertiaVueComponent::class,
            ]);
        }
    }
}
