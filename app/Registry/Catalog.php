<?php

declare(strict_types=1);

namespace App\Registry;

use App\Enums\RegistryStack;

final class Catalog
{
    private const string ALPINE_SETUP = "Behavior comes from the Crux UI Alpine plugin. Register it once: import Alpine from 'alpinejs'; import CruxUI from 'crux-ui'; Alpine.plugin(CruxUI); Alpine.start()";

    /**
     * @return list<RegistryItem>
     */
    public static function items(RegistryStack $stack): array
    {
        return [
            new RegistryItem(
                name: 'theme',
                title: 'Theme',
                description: 'The shadcn CSS variables every component reads.',
                files: [new RegistryFile(source: 'css/theme.css', target: 'resources/css/theme.css')],
                docs: "Import it from your stylesheet after Tailwind: @import './theme.css';",
            ),
            new RegistryItem(
                name: 'button',
                title: 'Button',
                description: 'A button with variants and sizes.',
                files: [$stack->componentFile('button')],
                registryDependencies: ['theme'],
            ),
            new RegistryItem(
                name: 'collapsible',
                title: 'Collapsible',
                description: 'A panel controlled by a button.',
                files: [
                    $stack->componentFile('collapsible'),
                    $stack->componentFile('collapsible-trigger'),
                    $stack->componentFile('collapsible-panel'),
                ],
                dependencies: ['crux-ui'],
                registryDependencies: ['theme'],
                docs: self::ALPINE_SETUP,
            ),
        ];
    }
}
