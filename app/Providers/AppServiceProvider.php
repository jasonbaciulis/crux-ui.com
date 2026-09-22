<?php

declare(strict_types=1);

namespace App\Providers;

use App\Registry\RegistryBuilder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as RenderedView;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RegistryBuilder::class, fn (): RegistryBuilder => new RegistryBuilder(
            files: new Filesystem,
            resourcesPath: resource_path(),
        ));
    }

    public function boot(): void
    {
        $this->loadBladeStackViews();
        $this->loadAppBundleIntoDocs();
    }

    /**
     * Each stack keeps its components and demos under its own prefix (views/blade, views/antlers).
     * Adding views/blade as a view location keeps `<x-ui.*>` and `demos.*` resolving exactly as they
     * do in a user's project, so the shipped source stays honest.
     */
    private function loadBladeStackViews(): void
    {
        View::addLocation(resource_path('views/blade'));
    }

    /**
     * The docs layout stays unpublished, so the registry's Tailwind and Alpine bundle
     * is pushed onto its `head` stack instead of being hardcoded into a vendor copy.
     */
    private function loadAppBundleIntoDocs(): void
    {
        View::composer('laradocs::layout', function (RenderedView $view): void {
            $view->getFactory()->startPush(
                'head',
                Vite::withEntryPoints(['resources/css/app.css', 'resources/js/app.js'])->toHtml(),
            );
        });
    }
}
