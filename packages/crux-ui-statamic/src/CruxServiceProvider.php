<?php

declare(strict_types=1);

namespace CruxUI\Statamic;

use CruxUI\Statamic\Console\AddCommand;
use CruxUI\Statamic\Registry\RegistryClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

final class CruxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Installer::class, fn (Application $app): Installer => new Installer(
            files: new Filesystem,
            client: new RegistryClient,
            projectRoot: $app->basePath(),
        ));
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([AddCommand::class]);
        }
    }
}
