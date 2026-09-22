{{-- laradocs macro view: <x-demo-source name="collapsible" /> emits the demo's Blade source as a code tab, so the docs can never drift from the demo. --}}
```blade tab:Blade
{!! trim(file_get_contents(resource_path("views/demos/{$name}.blade.php"))) !!}
```
