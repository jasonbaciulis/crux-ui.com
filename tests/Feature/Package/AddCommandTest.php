<?php

declare(strict_types=1);

use CruxUI\Statamic\Enums\Stack;
use CruxUI\Statamic\Installer;
use CruxUI\Statamic\Registry\RegistryClient;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * @param  list<array{target: string, content: string}>  $files
 * @param  array<string, mixed>  $extra
 * @return array<string, mixed>
 */
function registryItem(string $name, array $files, array $extra = []): array
{
    return [
        'name' => $name,
        'type' => 'registry:item',
        'files' => array_map(fn (array $file): array => [...$file, 'type' => 'registry:file'], $files),
        ...$extra,
    ];
}

beforeEach(function (): void {
    $this->projectRoot = storage_path('framework/testing/project');
    File::deleteDirectory($this->projectRoot);
    File::ensureDirectoryExists($this->projectRoot);

    $this->app->instance(Installer::class, new Installer(new Filesystem, new RegistryClient, $this->projectRoot));

    Http::fake([
        'https://crux-ui.com/r/blade/theme.json' => Http::response(registryItem('theme', [
            ['target' => 'resources/css/theme.css', 'content' => ':root { --radius: 1rem }'],
        ], ['docs' => 'Import it from your stylesheet.'])),
        'https://crux-ui.com/r/blade/collapsible.json' => Http::response(registryItem('collapsible', [
            ['target' => 'resources/views/components/ui/collapsible.blade.php', 'content' => '<div x-data x-collapsible>'],
        ], ['dependencies' => ['crux-ui'], 'registryDependencies' => ['@crux/theme'], 'docs' => 'Register the Alpine plugin.'])),
        'https://crux-ui.com/r/antlers/theme.json' => Http::response(registryItem('theme', [
            ['target' => 'resources/css/theme.css', 'content' => ':root {}'],
        ])),
        'https://crux-ui.com/r/antlers/button.json' => Http::response(registryItem('button', [
            ['target' => 'resources/views/components/ui/button.antlers.html', 'content' => '<button>{{ slot }}</button>'],
        ], ['registryDependencies' => ['theme']])),
        'https://crux-ui.com/r/blade/missing.json' => Http::response(status: 404),
        'https://crux-ui.com/r/blade/broken.json' => Http::response(status: 500),
        'https://crux-ui.com/r/blade/malformed.json' => Http::response(['name' => 'malformed', 'files' => 'nope']),
        'https://crux-ui.com/r/blade/text.json' => Http::response('plain text'),
        'https://registry.test/r/blade/theme.json' => Http::response(registryItem('theme', [
            ['target' => 'theme.css', 'content' => 'custom'],
        ])),
    ]);
});

afterEach(function (): void {
    File::deleteDirectory($this->projectRoot);
});

it('installs an item with its registry dependencies, in dependency order', function (): void {
    $this->artisan('crux:add', ['names' => ['collapsible'], '--stack' => 'blade'])
        ->expectsOutputToContain('written  resources/css/theme.css')
        ->expectsOutputToContain('written  resources/views/components/ui/collapsible.blade.php')
        ->expectsOutputToContain('Installed for the blade stack.')
        ->expectsOutputToContain('bun add crux-ui')
        ->expectsOutputToContain('Register the Alpine plugin.')
        ->expectsOutputToContain('Import it from your stylesheet.')
        ->assertSuccessful();

    expect(File::get($this->projectRoot.'/resources/css/theme.css'))->toBe(':root { --radius: 1rem }')
        ->and(File::get($this->projectRoot.'/resources/views/components/ui/collapsible.blade.php'))->toBe('<div x-data x-collapsible>');
});

it('keeps existing files unless forced', function (): void {
    File::ensureDirectoryExists($this->projectRoot.'/resources/css');
    File::put($this->projectRoot.'/resources/css/theme.css', 'mine');

    $this->artisan('crux:add', ['names' => ['theme'], '--stack' => 'blade'])
        ->expectsOutputToContain('kept     resources/css/theme.css (pass --force to overwrite)')
        ->assertSuccessful();

    expect(File::get($this->projectRoot.'/resources/css/theme.css'))->toBe('mine');

    $this->artisan('crux:add', ['names' => ['theme'], '--stack' => 'blade', '--force' => true])
        ->expectsOutputToContain('written  resources/css/theme.css')
        ->assertSuccessful();

    expect(File::get($this->projectRoot.'/resources/css/theme.css'))->toBe(':root { --radius: 1rem }');
});

it('accepts the shadcn namespace prefix and installs the antlers stack on request', function (): void {
    $this->artisan('crux:add', ['names' => ['@crux/button'], '--stack' => 'antlers'])
        ->expectsOutputToContain('Installed for the antlers stack.')
        ->assertSuccessful();

    expect(File::exists($this->projectRoot.'/resources/views/components/ui/button.antlers.html'))->toBeTrue();
});

it('detects the stack when none is given', function (): void {
    Http::fake([
        'https://crux-ui.com/r/*/theme.json' => Http::response(registryItem('theme', [
            ['target' => 'theme.css', 'content' => 'detected'],
        ])),
    ]);

    $this->artisan('crux:add', ['names' => ['theme']])
        ->expectsOutputToContain(sprintf('Installed for the %s stack.', Stack::detect()->value))
        ->assertSuccessful();
});

it('reads from another registry url', function (): void {
    $this->artisan('crux:add', ['names' => ['theme'], '--stack' => 'blade', '--registry' => 'https://registry.test/r/'])
        ->assertSuccessful();

    expect(File::get($this->projectRoot.'/theme.css'))->toBe('custom');
});

it('rejects an unknown stack', function (): void {
    $this->artisan('crux:add', ['names' => ['theme'], '--stack' => 'twig'])
        ->expectsOutputToContain('Unknown stack "twig". Use blade or antlers.')
        ->assertFailed();
});

it('fails clearly when an item does not exist', function (): void {
    $this->artisan('crux:add', ['names' => ['missing'], '--stack' => 'blade'])
        ->expectsOutputToContain('No registry item named "missing" at https://crux-ui.com/r/blade/missing.json')
        ->assertFailed();
});

it('fails clearly when the registry is unreachable', function (): void {
    $this->artisan('crux:add', ['names' => ['broken'], '--stack' => 'blade'])
        ->expectsOutputToContain('answered with HTTP 500')
        ->assertFailed();
});

it('installs an item shared by several requested items only once', function (): void {
    $this->artisan('crux:add', ['names' => ['theme', 'collapsible'], '--stack' => 'blade'])
        ->assertSuccessful();

    Http::assertSentCount(2);
});

it('fails clearly when an item is not a JSON object', function (): void {
    $this->artisan('crux:add', ['names' => ['text'], '--stack' => 'blade'])
        ->expectsOutputToContain('is not a JSON object')
        ->assertFailed();
});

it('fails clearly when an item is malformed', function (): void {
    $this->artisan('crux:add', ['names' => ['malformed'], '--stack' => 'blade'])
        ->expectsOutputToContain('has no valid "files" field')
        ->assertFailed();
});
