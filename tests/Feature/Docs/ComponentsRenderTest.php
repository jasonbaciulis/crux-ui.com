<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

it('renders every ui component with its data-slot and slot content', function (): void {
    $names = collect(File::files(resource_path('views/blade/components/ui')))
        ->map(fn ($file): string => str_replace('.blade.php', '', $file->getFilename()));

    expect($names)->not->toBeEmpty();

    foreach ($names as $name) {
        $html = Blade::render("<x-ui.{$name} class=\"extra\">Slot text</x-ui.{$name}>");

        expect($html)->toContain('data-slot="'.$name.'"', 'Slot text', 'extra');
    }
});

it('renders button variants and sizes', function (string $variant, string $size): void {
    $html = Blade::render("<x-ui.button variant=\"{$variant}\" size=\"{$size}\">Go</x-ui.button>");

    expect($html)->toContain('<button', 'type="button"', 'data-slot="button"');
})->with(['default', 'outline', 'secondary', 'ghost', 'destructive', 'link'])
    ->with(['default', 'xs', 'sm', 'lg', 'icon', 'icon-xs', 'icon-sm', 'icon-lg']);

it('renders the button as another element without a type attribute', function (): void {
    $html = Blade::render('<x-ui.button as="a" href="/docs">Docs</x-ui.button>');

    expect($html)->toContain('<a', 'href="/docs"')
        ->not->toContain('type="button"');
});

it('lets a submit button override the default type', function (): void {
    expect(Blade::render('<x-ui.button type="submit">Save</x-ui.button>'))->toContain('type="submit"');
});

it('server-renders the collapsible panel hidden state', function (): void {
    expect(Blade::render('<x-ui.collapsible-panel>A</x-ui.collapsible-panel>'))->toContain(' hidden')
        ->and(Blade::render('<x-ui.collapsible-panel :hidden="false">A</x-ui.collapsible-panel>'))->not->toContain('hidden')
        ->and(Blade::render('<x-ui.collapsible-panel hidden="until-found">A</x-ui.collapsible-panel>'))->toContain('hidden="until-found"');
});
