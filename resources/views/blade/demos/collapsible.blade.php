<x-ui.collapsible class="w-full max-w-sm rounded-md data-open:bg-muted">
    <x-ui.button x-collapsible:trigger variant="ghost" class="w-full">
        Product details
        <s:svg src="lucide.chevron-down" class="ml-auto transition-transform group-data-panel-open/button:rotate-180" />
    </x-ui.button>
    <x-ui.collapsible-panel class="flex flex-col items-start gap-2 p-2.5 pt-0 text-sm">
        <div>This panel can be expanded or collapsed to reveal additional content.</div>
        <x-ui.button size="xs">Learn More</x-ui.button>
    </x-ui.collapsible-panel>
</x-ui.collapsible>
