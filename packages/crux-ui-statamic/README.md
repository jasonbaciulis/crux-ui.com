# crux-ui/statamic

Installs [Crux UI](https://crux-ui.com) registry components into a Statamic or Laravel project.

```bash
composer require crux-ui/statamic --dev
php artisan crux:add collapsible
```

The command fetches the item from the Crux UI registry, installs the items it depends on, writes the files into `resources/views/components/ui/`, and prints the npm packages to add. It installs Antlers partials when Statamic is present and Blade components otherwise; pass `--stack=blade` or `--stack=antlers` to choose. Existing files are kept unless you pass `--force`.

## Development

This package lives inside the crux-ui.com docs repo and is tested by that app's suite (`tests/Feature/Package`), so one `composer test` at the repo root covers the docs, the registry build, and this command.
