<?php

declare(strict_types=1);

namespace CruxUI\Statamic;

final class InstallReport
{
    /** @var list<string> */
    public private(set) array $written = [];

    /** @var list<string> */
    public private(set) array $kept = [];

    /** @var list<string> */
    public private(set) array $npmDependencies = [];

    /** @var list<string> */
    public private(set) array $notes = [];

    public function written(string $target): void
    {
        $this->written[] = $target;
    }

    public function kept(string $target): void
    {
        $this->kept[] = $target;
    }

    public function needsNpm(string $package): void
    {
        if (! in_array($package, $this->npmDependencies, true)) {
            $this->npmDependencies[] = $package;
        }
    }

    public function note(string $note): void
    {
        if (! in_array($note, $this->notes, true)) {
            $this->notes[] = $note;
        }
    }
}
