<?php namespace App\Providers;

use App\Services\HostersService;
use Illuminate\Support\ServiceProvider;

class HostersServiceProvider extends ServiceProvider
{
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
        $this->app->singleton('Hosters', function($app)
        {
            return new HostersService();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Hosters'];
    }
}