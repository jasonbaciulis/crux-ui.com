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
