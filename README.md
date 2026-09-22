# crux-ui.com

The documentation site and component registry for [Crux UI](https://github.com/jasonbaciulis/crux-ui): headless, accessible Alpine.js primitives plus copy-and-own styled components for Blade and Antlers.

This repository is **not** the `crux-ui` npm package. It is the Laravel application that:

- Serves the docs at `/docs` using [Laradocs](https://github.com/petebishwhip/laradocs), with live component demos rendered from Blade.
- Holds the source of every styled component under `resources/views/components/ui/`.
- Compiles those components into a shadcn-shaped JSON registry under `public/r/`, which the `crux:add` command (from the `crux-ui/statamic` package) and the shadcn CLI install from.

## How the pieces fit

| Piece | Where it lives | What it does |
| --- | --- | --- |
| Primitives | `crux-ui` on npm | Alpine plugin exposing `x-collapsible`, etc. Handles ARIA, keyboard and focus. |
| Components | `resources/views/components/ui/` | Styled Blade components (`<x-ui.button>`, `<x-ui.card>`, ...) that use the primitives and the shadcn CSS variables. |
| Theme | `resources/css/theme.css` | The shadcn CSS variables every component reads. Published as the `theme` registry item. |
| Registry | `public/r/{blade,antlers}/*.json` | Generated output. Each item carries its files inline so a CLI can copy them into a project. |
| Docs | `docs/*.md` | Markdown pages with front-matter, served by Laradocs. |
| Demos | `resources/views/demos/*.blade.php` | Blade snippets rendered inside docs pages via the `<x-demo>` macro. |

## Requirements

- PHP 8.5+
- [Bun](https://bun.sh)
- A coverage driver such as [Xdebug](https://xdebug.org) for `composer test`

## Getting started

```bash
composer setup   # composer install, .env, key, migrate, bun install, bun run build
composer dev     # php artisan serve + queue + pail + vite, concurrently
```

The docs are then available at `http://localhost:8000/docs`.

## Building the registry

```bash
php artisan crux:registry-build
```

This reads the catalog in `app/Registry/Catalog.php` and writes, for each stack, one JSON file per item plus a `registry.json` index into `public/r/<stack>/`. Pass `--output=<dir>` to write elsewhere.

The output follows the [shadcn registry schema](https://ui.shadcn.com/schema/registry-item.json). Item names are namespaced as `@crux/<name>`, and `registryDependencies` point at other items (every component depends on `theme`).

Every catalog item must have a source file for every stack. A missing source fails the build.

## Adding a component

1. Create the Blade files in `resources/views/components/ui/`. Multi-part components use one file per part, for example `card.blade.php` and `card-header.blade.php`.
2. Register the item in `app/Registry/Catalog.php`. List the npm dependencies it needs (usually `crux-ui`) and the registry items it depends on (usually `theme`).
3. Add a demo in `resources/views/demos/<name>.blade.php`.
4. Create the docs page with `php artisan make:doc components/<name>` and use `<x-demo name="<name>" />` and `<x-demo-source name="<name>" />` to render the live demo and its source.
5. Run `php artisan crux:registry-build` and commit the generated JSON in `public/r/`.
6. Add or update the tests under `tests/Feature/Docs/` and `tests/Feature/Registry/`.

## Writing docs

Pages live in `docs/` and each needs a `title` in its front-matter. Folders become navigation sections and URL segments, so `docs/components/collapsible.md` is served at `/docs/components/collapsible`.

Two Laradocs macros are registered in `config/laradocs.php`:

- `<x-demo name="collapsible" />` renders the demo Blade view inside a preview frame.
- `<x-demo-source name="collapsible" />` emits that view's source as a code tab, so the docs cannot drift from the demo.

Run `php artisan docs:lint` before committing, and `php artisan laradocs:clear` after changing Laradocs config or macros.

## Tooling

- `composer lint` runs Rector, Pint and the Vite+ formatter.
- `composer test` runs 100% type coverage, Pest with 100% code coverage, lint checks and PHPStan at max level.
- `composer test:unit`, `composer test:types`, `composer test:lint` and `composer test:type-coverage` run each step alone.
- `php artisan test --compact --filter=<name>` runs a single test.

## License

MIT.
