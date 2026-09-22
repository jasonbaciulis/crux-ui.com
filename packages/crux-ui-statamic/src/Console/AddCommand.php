<?php

declare(strict_types=1);

namespace CruxUI\Statamic\Console;

use CruxUI\Statamic\Enums\Stack;
use CruxUI\Statamic\Exceptions\RegistryException;
use CruxUI\Statamic\Installer;
use CruxUI\Statamic\InstallReport;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Install Crux UI components from the registry into this project')]
#[Signature('crux:add
    {names* : Component names, for example button or collapsible}
    {--stack= : blade or antlers (detected from the project when omitted)}
    {--force : Overwrite files that already exist}
    {--registry=https://crux-ui.com/r : Registry base URL}')]
final class AddCommand extends Command
{
    public function handle(Installer $installer): int
    {
        $stack = $this->stack();

        if (! $stack instanceof Stack) {
            return self::FAILURE;
        }

        try {
            $report = $installer->install($this->names(), $stack, $this->registryUrl(), (bool) $this->option('force'));
        } catch (RegistryException $registryException) {
            $this->error($registryException->getMessage());

            return self::FAILURE;
        }

        $this->summarise($report, $stack);

        return self::SUCCESS;
    }

    private function stack(): ?Stack
    {
        $option = $this->option('stack');

        if (! is_string($option)) {
            return Stack::detect();
        }

        $stack = Stack::tryFrom($option);

        if (! $stack instanceof Stack) {
            $this->error(sprintf('Unknown stack "%s". Use blade or antlers.', $option));
        }

        return $stack;
    }

    /**
     * @return list<string>
     */
    private function names(): array
    {
        /** @var list<string> $names */
        $names = $this->argument('names');

        return $names;
    }

    private function registryUrl(): string
    {
        $registry = $this->option('registry');

        return is_string($registry) ? $registry : 'https://crux-ui.com/r';
    }

    private function summarise(InstallReport $report, Stack $stack): void
    {
        foreach ($report->written as $target) {
            $this->line(sprintf('  <info>written</info>  %s', $target));
        }

        foreach ($report->kept as $target) {
            $this->line(sprintf('  <comment>kept</comment>     %s (pass --force to overwrite)', $target));
        }

        $this->newLine();
        $this->info(sprintf('Installed for the %s stack.', $stack->value));

        if ($report->npmDependencies !== []) {
            $this->line(sprintf('Add the npm dependencies: <comment>bun add %s</comment>', implode(' ', $report->npmDependencies)));
        }

        foreach ($report->notes as $note) {
            $this->line($note);
        }
    }
}
