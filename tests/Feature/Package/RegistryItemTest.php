<?php

declare(strict_types=1);

use CruxUI\Statamic\Exceptions\RegistryException;
use CruxUI\Statamic\Registry\RegistryItem;

it('maps a registry document into a typed item', function (): void {
    $item = RegistryItem::fromArray([
        'name' => 'card',
        'files' => [['target' => 'a.blade.php', 'content' => 'A']],
        'dependencies' => [],
        'registryDependencies' => ['@crux/theme', 'button'],
    ], 'https://crux-ui.com/r/blade/card.json');

    expect($item->name)->toBe('card')
        ->and($item->files[0]->target)->toBe('a.blade.php')
        ->and($item->registryDependencies)->toBe(['theme', 'button'])
        ->and($item->docs)->toBeNull();
});

it('rejects documents with the wrong shape', function (array $document, string $field): void {
    RegistryItem::fromArray($document, 'https://crux-ui.com/r/blade/x.json');
})->with([
    'missing name' => [['files' => []], 'name'],
    'file is not an object' => [['name' => 'x', 'files' => ['nope']], 'files'],
    'file without content' => [['name' => 'x', 'files' => [['target' => 'a']]], 'content'],
    'dependencies not a list' => [['name' => 'x', 'files' => [], 'dependencies' => ['a' => 'b']], 'dependencies'],
    'dependencies not strings' => [['name' => 'x', 'files' => [], 'dependencies' => [1]], 'dependencies'],
])->throws(RegistryException::class, 'has no valid');
