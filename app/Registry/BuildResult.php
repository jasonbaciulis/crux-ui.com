<?php

declare(strict_types=1);

namespace App\Registry;

final readonly class BuildResult
{
    /**
     * @param  list<string>  $built
     */
    public function __construct(
        public array $built,
    ) {}
}
