<?php

declare(strict_types=1);

namespace App\Registry;

final readonly class RegistryFile
{
    /**
     * @param  string  $source  Path relative to the resources directory of this app.
     * @param  string  $target  Path relative to the root of the user's project.
     */
    public function __construct(
        public string $source,
        public string $target,
    ) {}
}
