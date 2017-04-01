<?php

namespace App\Providers;

use App\Services\ParserService;
use Illuminate\Support\ServiceProvider;

class ParserServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Parser', function()
        {
            return new ParserService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Parser'];
    }

}