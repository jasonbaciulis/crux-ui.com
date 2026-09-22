---
title: Installation
description: Add the Crux UI primitives to Alpine and install components with artisan.
group: Guides
order: 1
---

# Installation

## 1. Install the primitives

```bash
bun add alpinejs crux-ui
```

Register the plugin where you start Alpine:

```js
import Alpine from 'alpinejs';
import CruxUI from 'crux-ui';

Alpine.plugin(CruxUI);
Alpine.start();
```

## 2. Install the registry CLI

```bash
composer require crux-ui/laravel --dev
```

## 3. Add the theme and a component

```bash
php artisan crux:add theme
php artisan crux:add collapsible
```

Components are written into `resources/views/components/ui/` and become `<x-ui.*>` Blade components. In a Statamic project the command writes Antlers partials instead; pass `--stack=antlers` to force it.

> [!NOTE]
> The theme item adds the shadcn CSS variables to your stylesheet. Every component reads its colours and radii from those variables, so restyling means editing one file.
