{{-- laradocs macro view: <x-demo name="collapsible" /> in markdown renders resources/views/blade/demos/collapsible.blade.php --}}
@props(['center' => false])

<div
    data-slot="component-preview"
    class="flex min-h-72 w-full justify-center rounded-xl border border-border bg-background p-10 text-foreground {{ $center ? 'items-center' : 'items-start' }}"
>
    @include("demos.{$name}")
</div>
