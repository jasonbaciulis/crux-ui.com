{{-- laradocs macro view: <x-demo name="collapsible" /> in markdown renders resources/views/blade/demos/collapsible.blade.php --}}
<div
    data-slot="component-preview"
    class="flex min-h-72 w-full items-center justify-center rounded-xl border border-border bg-background p-10 text-foreground"
>
    @include("demos.{$name}")
</div>
