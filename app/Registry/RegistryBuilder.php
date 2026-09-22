<?php

declare(strict_types=1);

namespace App\Registry;

use App\Enums\RegistryStack;
use Illuminate\Filesystem\Filesystem;

/**
 * Emits shadcn-shaped item JSON, so the shadcn CLI and MCP server can consume the registry next to crux:add.
 * Every catalog item must exist in every stack; a missing source fails the build.
 */
final readonly class RegistryBuilder
{
    private const string HOMEPAGE = 'https://crux-ui.com';

    private const string NAMESPACE = '@crux';

    public function __construct(
        private Filesystem $files,
        private string $resourcesPath,
    ) {}

    public function build(RegistryStack $stack, string $outputDirectory): BuildResult
    {
        $directory = $outputDirectory.'/'.$stack->value;
        $this->files->ensureDirectoryExists($directory);

        $items = Catalog::items($stack);

        foreach ($items as $item) {
            $this->writeJson($directory.'/'.$item->name.'.json', $this->itemDocument($item));
        }

        $this->writeJson($directory.'/registry.json', $this->indexDocument($items));

        return new BuildResult(
            built: array_map(fn (RegistryItem $item): string => $item->name, $items),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function itemDocument(RegistryItem $item): array
    {
        return [
            '$schema' => 'https://ui.shadcn.com/schema/registry-item.json',
            ...$this->itemSummary($item),
            'files' => array_map(fn (RegistryFile $file): array => [
                ...$this->fileSummary($file),
                'content' => $this->files->get($this->resourcesPath.'/'.$file->source),
            ], $item->files),
        ];
    }

    /**
     * @param  list<RegistryItem>  $items
     * @return array<string, mixed>
     */
    private function indexDocument(array $items): array
    {
        return [
            '$schema' => 'https://ui.shadcn.com/schema/registry.json',
            'name' => 'crux',
            'homepage' => self::HOMEPAGE,
            'items' => array_map(fn (RegistryItem $item): array => [
                ...$this->itemSummary($item),
                'files' => array_map($this->fileSummary(...), $item->files),
            ], $items),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function itemSummary(RegistryItem $item): array
    {
        $summary = [
            'name' => $item->name,
            'type' => 'registry:item',
            'title' => $item->title,
            'description' => $item->description,
            'dependencies' => $item->dependencies,
            'registryDependencies' => array_map(
                fn (string $name): string => self::NAMESPACE.'/'.$name,
                $item->registryDependencies,
            ),
        ];

        if ($item->docs !== null) {
            $summary['docs'] = $item->docs;
        }

        return $summary;
    }

    /**
     * @return array<string, string>
     */
    private function fileSummary(RegistryFile $file): array
    {
        return [
            'path' => $file->source,
            'type' => 'registry:file',
            'target' => $file->target,
        ];
    }

    /**
     * @param  array<string, mixed>  $document
     */
    private function writeJson(string $path, array $document): void
    {
        $json = json_encode(
            $document,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        );

        $this->files->put($path, $json."\n");
    }
}
