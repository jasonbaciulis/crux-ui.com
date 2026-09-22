<?php

declare(strict_types=1);

namespace App\Providers;

use App\Registry\RegistryBuilder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RegistryBuilder::class, fn (): RegistryBuilder => new RegistryBuilder(
            files: new Filesystem,
            resourcesPath: resource_path(),
        ));
    }
}
