<?php

namespace A17\TwillRobotsTxt;

use Illuminate\Support\Str;
use A17\Twill\Facades\TwillCapsules;
use Illuminate\Support\Facades\Route;
use A17\TwillRobotsTxt\Services\Helpers;
use A17\Twill\TwillPackageServiceProvider;
use A17\TwillRobotsTxt\Support\TwillRobotsTxt;

class ServiceProvider extends TwillPackageServiceProvider
{
    /** @var bool $autoRegisterCapsules */
    protected $autoRegisterCapsules = false;

    public function boot(): void
    {
        $this->registerConfig();

        if (!config('twill-robots-txt.enabled', true)) {
            return;
        }

        $this->registerThisCapsule();

        $this->registerRoutes();

        $this->registerViews();

        parent::boot();
    }

    protected function registerThisCapsule(): void
    {
        $namespace = $this->getCapsuleNamespace();

        TwillCapsules::registerPackageCapsule(
            Str::afterLast($namespace, '\\'),
            $namespace,
            $this->getPackageDirectory() . '/src',
            null, // $singular
            true, // $enabled
            config('twill-robots-txt.navigation.automatic', true),
        );

        app()->singleton(TwillRobotsTxt::class, fn() => new TwillRobotsTxt());
    }

    public function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'twill-robots-txt');
    }

    public function registerConfig(): void
    {
        $package = 'twill-robots-txt';

        $path = __DIR__ . "/config/{$package}.php";

        $this->mergeConfigFrom($path, $package);

        $this->publishes([
            $path => config_path("{$package}.php"),
        ]);
    }

    public function registerRoutes()
    {
        Route::get('/robots.txt', [
            config('twill-robots-txt.route.controller'),
            config('twill-robots-txt.route.action'),
        ])->name('robots.txt');
    }
}
