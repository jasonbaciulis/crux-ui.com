<x-ui.card class="mx-auto w-full max-w-sm">
    <x-ui.card-content>
        <x-ui.collapsible class="rounded-md data-open:bg-muted">
            <x-ui.button x-collapsible:trigger variant="ghost" class="w-full">
                Product details
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto transition-transform group-data-panel-open/button:rotate-180" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
            </x-ui.button>
            <x-ui.collapsible-panel class="flex flex-col items-start gap-2 p-2.5 pt-0 text-sm">
                <div>This panel can be expanded or collapsed to reveal additional content.</div>
                <x-ui.button size="xs">Learn More</x-ui.button>
            </x-ui.collapsible-panel>
        </x-ui.collapsible>
    </x-ui.card-content>
</x-ui.card>
