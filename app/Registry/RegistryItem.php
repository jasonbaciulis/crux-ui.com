<?php

declare(strict_types=1);

namespace App\Registry;

final readonly class RegistryItem
{
    /**
     * @param  list<RegistryFile>  $files
     * @param  list<string>  $dependencies  npm packages the item needs.
     * @param  list<string>  $registryDependencies  Other items the item needs.
     */
    public function __construct(
        public string $name,
        public string $title,
        public string $description,
        public array $files,
        public array $dependencies = [],
        public array $registryDependencies = [],
        public ?string $docs = null,
    ) {}
}
