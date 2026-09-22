<?php

declare(strict_types=1);

namespace CruxUI\Statamic;

use CruxUI\Statamic\Enums\Stack;
use CruxUI\Statamic\Registry\RegistryClient;
use CruxUI\Statamic\Registry\RegistryItem;
use Illuminate\Filesystem\Filesystem;

final class Installer
{
    /** @var array<string, RegistryItem> keyed by item name, in dependency order */
    private array $resolved = [];

    public function __construct(
        private readonly Filesystem $files,
        private readonly RegistryClient $client,
        private readonly string $projectRoot,
    ) {}

    /**
     * @param  list<string>  $names
     */
    public function install(array $names, Stack $stack, string $registryUrl, bool $force): InstallReport
    {
        $this->resolved = [];

        foreach ($names as $name) {
            $this->resolve(RegistryItem::withoutNamespace($name), $stack, $registryUrl);
        }

        $report = new InstallReport;

        foreach ($this->resolved as $item) {
            $this->writeFiles($item, $force, $report);
            $this->collectFollowUps($item, $report);
        }

        return $report;
    }

    // Dependencies register before the item that needs them, so the report reads in install order.
    private function resolve(string $name, Stack $stack, string $registryUrl): void
    {
        if (isset($this->resolved[$name])) {
            return;
        }

        $item = $this->client->item($registryUrl, $stack, $name);

        foreach ($item->registryDependencies as $dependency) {
            $this->resolve($dependency, $stack, $registryUrl);
        }

        $this->resolved[$name] = $item;
    }

    private function writeFiles(RegistryItem $item, bool $force, InstallReport $report): void
    {
        foreach ($item->files as $file) {
            $path = $this->projectRoot.'/'.$file->target;

            if ($this->files->exists($path) && ! $force) {
                $report->kept($file->target);

                continue;
            }

            $this->files->ensureDirectoryExists(dirname($path));
            $this->files->put($path, $file->content);
            $report->written($file->target);
        }
    }

    private function collectFollowUps(RegistryItem $item, InstallReport $report): void
    {
        foreach ($item->dependencies as $package) {
            $report->needsNpm($package);
        }

        if ($item->docs !== null) {
            $report->note($item->docs);
        }
    }
}
