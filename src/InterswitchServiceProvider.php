<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */

 namespace Interswitch\Interswitch;

 use Illuminate\Support\ServiceProvider;

 class InterswitchServiceProvider extends ServiceProvider
 {
     public function boot()
     {
         $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
         $this->loadViewsFrom(__DIR__ . '/views', 'interswitch');
         $this->mergeConfigFrom(__DIR__ . '/config/interswitch.php', 'interswitch');

         $this->publishes([
             __DIR__ . '/../config/interswitch.php' => config_path('interswitch.php')
         ]);
     }

     public function register()
     {
         $this->app->bind('laravel-interswitch', function () {
             return new Interswitch;
         });
     }

     public function provides()
     {
         return ['laravel-interswitch'];
     }
 }