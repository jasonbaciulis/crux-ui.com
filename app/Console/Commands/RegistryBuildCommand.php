<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\RegistryStack;
use App\Registry\RegistryBuilder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Compile the Blade and Antlers component trees into registry JSON')]
#[Signature('crux:registry-build {--output= : Directory to write into (defaults to public/r)}')]
final class RegistryBuildCommand extends Command
{
    public function handle(RegistryBuilder $builder): int
    {
        $output = $this->option('output');
        $outputDirectory = is_string($output) ? $output : public_path('r');

        foreach (RegistryStack::cases() as $stack) {
            $result = $builder->build($stack, $outputDirectory);

            $this->info(sprintf('%s: built %s', $stack->value, implode(', ', $result->built)));
        }

        return self::SUCCESS;
    }
}
