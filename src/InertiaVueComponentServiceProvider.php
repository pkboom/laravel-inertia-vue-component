<?php

namespace Pkboom\InertiaVueComponent;

use Illuminate\Support\ServiceProvider;
use Pkboom\InertiaVueComponent\Commands\MakeInertiaVueComponent;

class InertiaVueComponentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeInertiaVueComponent::class,
            ]);
        }
    }
}
