{{-- Server-renders the native hidden attribute so a closed panel cannot flash open before Alpine starts; the primitive takes it over on init. Pass :hidden="false" when the root has default-open, or hidden="until-found" when it has hidden-until-found. --}}
@props(['hidden' => true])

<div
    x-collapsible:panel
    data-slot="collapsible-panel"
    {{ $attributes->merge(['hidden' => $hidden]) }}
>
    {{ $slot }}
</div>
