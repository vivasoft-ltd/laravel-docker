<?php
namespace Vivasoft\LaravelDocker;


use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Vivasoft\LaravelDocker\Console\Commands\DockerInstall;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../.docker' => base_path('.docker'),
            __DIR__. '/../docker-compose.yml' => base_path('docker-compose.yml'),
            __DIR__. '/../Dockerfile' => base_path('Dockerfile'),
        ], 'docker');
    }

    public function register()
    {
        $this->commands([
            DockerInstall::class,
        ]);
    }
}
