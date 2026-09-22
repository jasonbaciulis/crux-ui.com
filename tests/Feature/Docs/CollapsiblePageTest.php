<?php

declare(strict_types=1);

it('renders the live demo and the code tabs', function (): void {
    $this->get('/docs/components/collapsible')
        ->assertOk()
        ->assertSee('data-slot="component-preview"', false)
        ->assertSee('x-data x-collapsible data-slot="collapsible"', false)
        ->assertSee('x-collapsible:trigger', false)
        ->assertSee('x-collapsible:panel data-slot="collapsible-panel" hidden', false)
        ->assertSee('data-language="blade"', false)
        ->assertSee('data-language="html"', false);
});

it('loads the Vite bundle that boots Alpine', function (): void {
    $this->get('/docs/components/collapsible')
        ->assertOk()
        ->assertSee('build/assets/app-', false);
});
