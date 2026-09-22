<?php

declare(strict_types=1);

namespace App\Enums;

use App\Registry\RegistryFile;

enum RegistryStack: string
{
    case Blade = 'blade';
    case Antlers = 'antlers';

    /**
     * The docs app keeps each stack under its own prefix (Statamic registers .antlers.html
     * ahead of .blade.php in the view finder); the install target has no prefix.
     */
    public function componentFile(string $name): RegistryFile
    {
        $fileName = $name.$this->fileExtension();

        return new RegistryFile(
            source: $this->sourceDirectory().'/'.$fileName,
            target: 'resources/views/components/ui/'.$fileName,
        );
    }

    private function sourceDirectory(): string
    {
        return 'views/'.$this->value.'/components/ui';
    }

    private function fileExtension(): string
    {
        return match ($this) {
            self::Blade => '.blade.php',
            self::Antlers => '.antlers.html',
        };
    }
}
