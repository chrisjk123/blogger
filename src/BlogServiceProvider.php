<?php

namespace Chriscreates\Blog;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * All of the event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        \Chriscreates\Blog\Events\PostViewed::class => [
            \Chriscreates\Blog\Listeners\StoreViewData::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->handleEvents();
        $this->handleRoutes();
        $this->handleMigrations();
        $this->handlePublishing();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->handleConfig();
        $this->handleCommands();
    }

    /**
     * Register the events and listeners.
     *
     * @return void
     * @throws BindingResolutionException
     */
    private function handleEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function handleRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Get the Blog route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        // TODO
        return [
            'namespace' => 'Blog\Http\Controllers',
            'prefix' => config('blog.path'),
            'middleware' => config('blog.middleware'),
        ];
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function handleMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations/');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function handlePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/factories/' => database_path('factories'),
            ], 'blog-factories');

            $this->publishes([
                __DIR__.'/../database/seeds/' => database_path('seeds'),
            ], 'blog-seeders');

            $this->publishes([
                __DIR__.'/../config/blogs.php' => config_path('blogs.php'),
            ], 'blog-config');
        }
    }

    /**
     * @return void
     */
    private function handleConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/blog.php',
            'config'
        );
    }

    /**
     * @return void
     */
    private function handleCommands()
    {
        $this->commands([
            \Chriscreates\Blog\Console\Commands\InstallCommand::class,
            \Chriscreates\Blog\Console\Commands\PublishCommand::class,
            \Chriscreates\Blog\Console\Commands\SetupCommand::class,
        ]);
    }
}