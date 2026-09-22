---
title: Introduction
description: Crux UI is a set of headless, accessible Alpine.js primitives with a copy-and-own component registry for Blade and Antlers.
order: 1
---

# Crux UI

Crux UI is the "Base UI for Alpine.js": headless, unstyled, accessible primitives that ship as one npm package, plus a registry of styled Blade and Antlers components that you copy into your project and own.

- **Primitives** (`crux-ui` on npm) add the behaviour: WAI-ARIA roles, keyboard navigation, focus management and state, exposed as `x-*` directives. Accessibility fixes reach you with `npm update`.
- **Components** (this registry) add the markup and the Tailwind classes. They follow the shadcn vocabulary, so `bg-primary`, `text-muted-foreground` and `data-slot` hooks mean what you expect. You install a component with one artisan command and edit the file freely.

> [!TIP]
> Start with the [installation guide](/docs/guides/installation), then browse the [components](/docs/components/collapsible).
