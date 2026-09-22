<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Registry;

final readonly class RegistryFile
{
    /**
     * @param  string  $target  Path relative to the root of the project.
     */
    public function __construct(
        public string $target,
        public string $content,
    ) {}
}
