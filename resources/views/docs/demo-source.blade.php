{{-- laradocs macro view: <x-demo-source name="collapsible" stack="antlers" /> emits a demo's source as a code tab, so the docs can never drift from the demos. --}}
@php
    $stack ??= 'blade';
    $path = $stack === 'antlers' ? "views/antlers/demos/{$name}.antlers.html" : "views/demos/{$name}.blade.php";
    $language = $stack === 'antlers' ? 'html' : 'blade';
@endphp
```{{ $language }} tab:{{ ucfirst($stack) }}
{!! trim(file_get_contents(resource_path($path))) !!}
```
