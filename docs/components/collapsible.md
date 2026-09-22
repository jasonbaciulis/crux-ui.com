---
title: Collapsible
description: A panel controlled by a button. The trigger toggles the panel open and closed.
group: Components
order: 1
---

# Collapsible

<x-demo name="collapsible" />

<x-demo-source name="collapsible" stack="blade" />
<x-demo-source name="collapsible" stack="antlers" />

## Installation

```bash
php artisan crux:add collapsible
```

## Directives

The component is one Alpine directive from the `crux-ui` package, with the part name as its argument. Parts resolve their root through Alpine's scope chain, so a nested collapsible binds to the nearest root.

| Directive | Effect |
|---|---|
| `x-collapsible` | Holds the open state. Reads `default-open`, `disabled` and `hidden-until-found`, scopes the generated ids, and dispatches `collapsible-change`. |
| `x-collapsible:trigger` | Toggles on click, Enter and Space. Binds `aria-expanded` and `aria-controls`. A native `<button>` gets `type="button"` and `disabled`; anything else gets `role="button"`, `tabindex="0"` and `aria-disabled`. |
| `x-collapsible:panel` | Shows and hides through `x-show`, or through `hidden="until-found"` when the root has `hidden-until-found`. Gets a generated `id` unless it already has one, and drops the server-rendered `hidden` on init. |

The root needs a bare `x-data` unless an ancestor already has one, because Alpine's initial scan only visits `x-data` elements. Parts are not tied to specific components: the demo above puts `x-collapsible:trigger` on `<x-ui.button>`.

## Configuration

Set these as plain attributes on the root.

| Attribute | Effect |
|---|---|
| `default-open` | Starts open. Pass `:hidden="false"` to the panel so the server-rendered markup agrees. |
| `disabled` | The trigger ignores clicks and keys. `x-model` and `$collapsible` still work. |
| `hidden-until-found` | Closed panels use `hidden="until-found"`, so find-in-page opens them. Pass `hidden="until-found"` to the panel. |
| `x-model` | Two-way binds a boolean. Overrides `default-open`. |

## Styling

State is exposed as boolean data attributes, so Tailwind variants like `data-open:` work without JavaScript.

| Attribute | Where | Present when |
|---|---|---|
| `data-open` / `data-closed` | root, panel | open / closed |
| `data-panel-open` | trigger | its panel is open |
| `data-disabled` | root, trigger | the root is disabled |

The open panel also exposes `--collapsible-panel-height` and `--collapsible-panel-width` as CSS variables. Add `x-collapse` to the panel to animate it.

## Events

`collapsible-change` bubbles from the root with `detail.value` set to the new boolean state.

```html
<div x-data x-collapsible @collapsible-change="console.log($event.detail.value)">
```

## Magic

`$collapsible` resolves the nearest root from wherever it is used.

| Member | Type |
|---|---|
| `$collapsible.isOpen` | `boolean` |
| `$collapsible.open()` | `void` |
| `$collapsible.close()` | `void` |
| `$collapsible.toggle()` | `void` |
