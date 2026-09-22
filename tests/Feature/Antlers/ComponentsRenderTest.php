<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Statamic\Facades\Antlers;

/**
 * The docs app keeps the Antlers tree under an antlers/ prefix; a user's project does not.
 * Rendering through the user-facing partial path keeps the shipped source honest.
 */
function renderAntlers(string $template): string
{
    $template = str_replace('partial:components/ui/', 'partial:antlers/components/ui/', $template);

    // Untrusted parsing (the default) refuses tags; the templates here are our own files.
    return (string) Antlers::parse($template, [], trusted: true);
}

function antlersPair(string $name, string $parameters, string $slot): string
{
    return sprintf('{{ partial:components/ui/%s %s }}%s{{ /partial:components/ui/%s }}', $name, $parameters, $slot, $name);
}

it('renders every antlers component with its data-slot and slot content', function (): void {
    $names = collect(File::files(resource_path('views/antlers/components/ui')))
        ->map(fn ($file): string => str_replace('.antlers.html', '', $file->getFilename()));

    expect($names)->not->toBeEmpty();

    foreach ($names as $name) {
        $html = renderAntlers(antlersPair($name, 'class="extra"', 'Slot text'));

        expect($html)->toContain('data-slot="'.$name.'"', 'Slot text', 'extra')
            ->not->toContain('{{');
    }
});

it('renders the antlers demo with the same markup contract as the blade demo', function (): void {
    $html = renderAntlers(File::get(resource_path('views/antlers/demos/collapsible.antlers.html')));

    expect($html)->toContain(
        'x-data x-collapsible data-slot="collapsible"',
        'type="button" x-collapsible:trigger',
        'data-slot="collapsible-panel" hidden',
        'Learn More',
    )->not->toContain('{{');
});

it('renders antlers button variants and sizes', function (string $variant, string $size): void {
    $html = renderAntlers(antlersPair('button', sprintf('variant="%s" size="%s"', $variant, $size), 'Go'));

    expect($html)->toContain('<button', 'type="button"', 'data-slot="button"')->not->toContain('{{');
})->with(['default', 'outline', 'secondary', 'ghost', 'destructive', 'link'])
    ->with(['default', 'xs', 'sm', 'lg', 'icon', 'icon-xs', 'icon-sm', 'icon-lg']);

it('renders the antlers button as another element without a type attribute', function (): void {
    $html = renderAntlers(antlersPair('button', 'element="a" attrs=\'href="/docs"\'', 'Docs'));

    expect($html)->toContain('<a', 'href="/docs"')->not->toContain('type="button"');
});

it('server-renders the antlers collapsible panel hidden state', function (): void {
    expect(renderAntlers(antlersPair('collapsible-panel', '', 'A')))->toContain(' hidden')
        ->and(renderAntlers(antlersPair('collapsible-panel', 'hidden="false"', 'A')))->not->toContain('hidden')
        ->and(renderAntlers(antlersPair('collapsible-panel', 'hidden="until-found"', 'A')))->toContain('hidden="until-found"');
});
