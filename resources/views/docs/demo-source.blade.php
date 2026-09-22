{{-- laradocs macro view: <x-demo-source name="collapsible" stack="antlers" /> emits a demo's source as a code tab, so the docs can never drift from the demos. --}}
@php
    $stack ??= 'blade';
    $extension = $stack === 'antlers' ? 'antlers.html' : 'blade.php';
    $language = $stack === 'antlers' ? 'html' : 'php';
@endphp
```{{ $language }} tab:{{ ucfirst($stack) }}
{!! trim(file_get_contents(resource_path("views/{$stack}/demos/{$name}.{$extension}"))) !!}
```
