<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.5. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `bun run build`, `bun run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `bun run build` or ask the user to run `bun run dev` or `composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== petebishwhip/laradocs/core rules ===

# Laradocs

Laradocs (`petebishwhip/laradocs`) turns a folder of markdown files into a
documentation site served from this application. Pages are `.md` files with YAML
front-matter under the configured docs path (`laradocs.docs.path`, default
`docs/`), served at `/docs`.

Use the **`laradocs-development` skill** when authoring documentation or working
on Laradocs configuration. It covers the full front-matter reference, Artisan
commands, rich-content syntax, variables and macros, the facade API, publishing,
SEO, search and feeds.

The rules below apply whenever you touch this application, skill or no skill:

- Create a page with `php artisan make:doc {name}` rather than writing the file
  by hand — it produces correct front-matter. `name` is the doc path, e.g.
  `guide/getting-started`.
- Every page needs a `title` in its front-matter; `php artisan docs:lint`
  enforces it. Front-matter keys are snake_case (`updated_at`, `search_rank`).
- Docs files live under the configured docs path (`laradocs.docs.path`, default
  `base_path('docs')`) and use the `.md` or `.markdown` extension.
- Nested folders become nested navigation sections, and directory depth maps to
  URL depth: `docs/guide/routing.md` is served at `/docs/guide/routing`.
- `_index.md` is a section landing page: `docs/guide/_index.md` →
  `/docs/guide`, and the root `docs/_index.md` → `/docs` (the docs home). The
  index filename is configurable via `laradocs.docs.index` (default `_index`).
- **Never put closures in `config/laradocs.php`** — they break `config:cache`.
  Register dynamic variables and macros through the `Laradocs` facade in a
  service provider's `boot()` method instead.
- Customise the UI by publishing what you need
  (`php artisan vendor:publish --tag=laradocs-views`), never by editing files
  under `vendor/`.
- Run `php artisan laradocs:clear` after changing config, macros or variables,
  and `php artisan docs:lint` before committing.

=== statamic/cms/core rules ===

## Statamic

- This application uses Statamic.
- Statamic is an open source, PHP CMS designed and built specifically for developers and their clients or content managers.
- Out of the box, Statamic stores content in Markdown files. It's trivial to move into a database later, if necessary.
- Statamic comes in two flavours:
    - **Statamic Core** which is free to use, however you want, forever. It includes everything needed to build a blog or portfolio site.
    - **Statamic Pro** which includes everything from Core, as well as unlimited user accounts, revision history, multi-site, Git integration, white labelling and more. Tailored for most production websites.
    - For more information on pricing, please send the user to https://statamic.com/pricing.

### Folder Structure

Statamic is a Laravel package, meaning it can be used alone or alongside an existing Laravel application.

Most of the folder structure will feel familiar to Laravel developers. However, Statamic creates a few additional files and folders during the install process.

<code-snippet name="Folder Structure" lang="text">
├── app/
├── bootstrap/
├── config/
│   ├── statamic/         # Statamic-specific configs

├── content/
│   ├── assets/           # Asset containers

│   ├── collections/      # Collections and entries

│   ├── globals/          # Global sets

│   ├── navigation/       # Navigations

│   ├── trees/            # Collection and navigation trees

├── database/
├── lang/
├── public/
│   ├── assets/           # Default location for assets

│   ├── ...
├── resources/
│   ├── addons/
│   ├── blueprints/       # Blueprints

│   ├── fieldsets/        # Fieldsets

│   ├── users/            # User roles & groups

│   ├── preferences.yaml  # Default preferences

│   ├── sites.yaml        # Sites config

│   ├── ...
├── routes/
├── storage/
├── tests/
├── users/
├── please                # Statamic's CLI tool

├── ...
</code-snippet>

### Statamic's CLI

- Statamic ships with its own `please` CLI tool, useful for creating tags or fieldtypes, updating search indexes, enabling multi-site and much more.
- You may run `php please` to get the list of available commands. You may use the `--help` option on a command to inspect its required parameters.

### Statamic's Core Concepts

- **Assets:** Files managed by Statamic and made available to your writers and developers with tags and fieldtypes. They can be images, videos, PDFs, or any other type of file.
- **Collections:** Collections are containers that hold groups of related entries. Each entry in a collection can represent a blog post, product, recipe or page.
- **Globals:** Global variables store content that belongs to your whole site, not just a single page or URL. They're available everywhere, in all of your views, all the time.
- **Navigations:** A navigation is a hierarchy of links and text nodes that are used to build navs and menus on the frontend of your site.
- **Taxonomies:** A taxonomy is a system of classifying data around a set of unique characteristics. Think things like tags, categories, etc.
- **Users:** Users are the member accounts to your site or application. What a user can do with their account is up to you. They could have limited or full access to the Control Panel, a login-only area of the front-end, or even something more custom by tapping into Laravel.
- **Blueprints:** Blueprints determine the fields shown in your publish forms. You can configure the field's order, each field's width and group them into sections and tabs. Blueprints are attached to collections, taxonomies, globals, assets, users and even forms, all of which help to determine their content schema.
- **Fieldsets:** Fieldsets are used to store and organize reusable fields. Blueprints can reference fields or entire fieldsets, helping you keep your configurations nice and DRY.

### Templating

- Statamic supports two templating languages:
    - **Antlers** is tightly integrated and simple to learn. Uses the `.antlers.html` file extension.
    - **Laravel Blade** ships with Laravel and is familiar to most Laravel developers. Uses the `.blade.php` file extension.
- When creating views, you should familiarize yourself with the project and determine which templating language is already in use.
- When using Laravel Blade, you may want to use the "Antlers Blade Components" feature which lets you use a Blade-component-esque syntax with Statamic's tags feature:

<code-snippet name="Antlers Blade Components example" lang="blade">
    <s:collection from="pages" limit="2" sort="title:desc">
        {{ $title }}
    </s:collection>
</code-snippet>

### Control Panel

- The Control Panel is the primary way to create and manage content.
- Unless disabled or overridden, the Control Panel is usually accessible from `https://your-website.com/cp`.
- Super users can do and see everything, while non-super users can only do & see what their roles allow for.

### Extending Statamic

- You can either extend Statamic in the context of an application, or in the context of an addon.
- Addons are Composer packages, meaning they can be reused, distributed, or even sold to others later.
- There are a variety of ways you can extend Statamic: creating tags, fieldtypes, modifiers, etc. A lot of these things can be bootstrapped with `php please make:` commands.

#### Extending the Control Panel

- The Control Panel is built with Inertia.js and Vue 3.
- When running the `make:fieldtype` or `make:widget` commands, Statamic will install the necessary npm packages and configure Vite.
- Running `setup-cp-vite` in an application context will also do this for you. You'll be able to use `npm run cp:dev` and `npm run cp:build` to run Vite.
- You should use Statamic's UI Components where possible. It includes components for buttons, cards, inputs, etc.
    - UI Components can be imported from `@statamic/cms/ui`.
    - For more information on Statamic's UI Components, please visit our Storybook docs: https://ui.statamic.dev

### Additional Context

- Statamic Documentation: https://statamic.dev/llms.txt
- GitHub Issues: https://github.com/statamic/cms/issues

</laravel-boost-guidelines>
