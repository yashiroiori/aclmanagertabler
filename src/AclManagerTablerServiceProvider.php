<?php

namespace Yashiroiori\AclManagerTabler;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yashiroiori\AclManagerTabler\Commands\InstallCommand;

class AclManagerTablerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'yashiroiori');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'yashiroiori');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->app->register(AclmanagerAuthServiceProvider::class);
        Route::bind('role', function ($id) {
            return Role::withTrashed()->findOrFail($id);
        });
        Route::bind('user', function ($id) {
            return User::withTrashed()->findOrFail($id);
        });
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
        include __DIR__ . '/breadcrumbs.php';
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/aclmanagertabler.php', 'aclmanagertabler');

        // Register the service the package provides.
        $this->app->singleton('aclmanagertabler', function ($app) {
            return new AclManagerTabler;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['aclmanagertabler'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/aclmanagertabler.php' => config_path('aclmanagertabler.php'),
        ], 'aclmanagertabler.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/yashiroiori'),
        ], 'aclmanagertabler.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/yashiroiori'),
        ], 'aclmanagertabler.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/yashiroiori'),
        ], 'aclmanagertabler.views');*/

        // Registering package commands.
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
