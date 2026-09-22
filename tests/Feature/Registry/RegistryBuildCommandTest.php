<?php

declare(strict_types=1);

use App\Registry\RegistryBuilder;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

/**
 * @return array<string, mixed>
 */
function readRegistryJson(string $path): array
{
    /** @var array<string, mixed> $document */
    $document = json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);

    return $document;
}

beforeEach(function (): void {
    $this->output = storage_path('framework/testing/registry');
    File::deleteDirectory($this->output);
});

afterEach(function (): void {
    File::deleteDirectory($this->output);
});

it('builds every blade item from the component tree', function (): void {
    $this->artisan('crux:registry-build', ['--output' => $this->output])
        ->expectsOutputToContain('blade: built theme, button, collapsible')
        ->assertSuccessful();

    $collapsible = readRegistryJson($this->output.'/blade/collapsible.json');

    expect($collapsible['name'])->toBe('collapsible')
        ->and($collapsible['type'])->toBe('registry:item')
        ->and($collapsible['dependencies'])->toBe(['crux-ui'])
        ->and($collapsible['registryDependencies'])->toBe(['@crux/theme'])
        ->and($collapsible['docs'])->toContain('Alpine.plugin(CruxUI)')
        ->and($collapsible['files'])->toHaveCount(3)
        ->and($collapsible['files'][0])->toMatchArray([
            'path' => 'views/blade/components/ui/collapsible.blade.php',
            'type' => 'registry:file',
            'target' => 'resources/views/components/ui/collapsible.blade.php',
            'content' => File::get(resource_path('views/blade/components/ui/collapsible.blade.php')),
        ]);
});

it('writes an index that lists items without file contents', function (): void {
    $this->artisan('crux:registry-build', ['--output' => $this->output])->assertSuccessful();

    $index = readRegistryJson($this->output.'/blade/registry.json');
    $names = array_column($index['items'], 'name');

    expect($index['name'])->toBe('crux')
        ->and($names)->toBe(['theme', 'button', 'collapsible'])
        ->and($index['items'][0]['files'][0])->toBe([
            'path' => 'css/theme.css',
            'type' => 'registry:file',
            'target' => 'resources/css/theme.css',
        ])
        ->and($index['items'][0]['docs'])->toContain('@import');
});

it('builds the antlers stack from its own tree with the install target unprefixed', function (): void {
    $this->artisan('crux:registry-build', ['--output' => $this->output])
        ->expectsOutputToContain('antlers: built theme, button, collapsible')
        ->assertSuccessful();

    $button = readRegistryJson($this->output.'/antlers/button.json');

    expect($button['files'][0])->toMatchArray([
        'path' => 'views/antlers/components/ui/button.antlers.html',
        'target' => 'resources/views/components/ui/button.antlers.html',
        'content' => File::get(resource_path('views/antlers/components/ui/button.antlers.html')),
    ]);
});

it('fails when a stack is missing a source file', function (): void {
    $resources = storage_path('framework/testing/resources');
    File::deleteDirectory($resources);
    File::ensureDirectoryExists($resources.'/css');
    File::put($resources.'/css/theme.css', ':root {}');
    $this->app->bind(RegistryBuilder::class, fn (): RegistryBuilder => new RegistryBuilder(new Filesystem, $resources));

    expect(fn () => $this->artisan('crux:registry-build', ['--output' => $this->output]))
        ->toThrow(FileNotFoundException::class);

    File::deleteDirectory($resources);
});

it('defaults to the public registry directory', function (): void {
    File::deleteDirectory(public_path('r'));

    $this->artisan('crux:registry-build')->assertSuccessful();

    expect(File::exists(public_path('r/blade/registry.json')))->toBeTrue();

    File::deleteDirectory(public_path('r'));
});
