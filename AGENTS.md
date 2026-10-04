# Project Overview

This is a Laravel learning project for a Product Management CRUD application.

## Learning Goal

Learn web development with Laravel using an AI-native workflow. AI may generate code, but each change must be explainable, reviewable, testable, and verifiable.

## Technology Stack

- Laravel, PHP, MySQL, Blade, Tailwind CSS, and Vite
- Flowbite or other pre-built Tailwind components may be considered later
- Docker is planned for the deployment stage

## Planned Core Feature

Product CRUD: list, view details, create, update, and delete products. Likely fields: `id`, `name`, `sku`, `price`, `stock`, `description`, `status`, `created_at`, and `updated_at`. Do not implement CRUD yet unless asked.

## Architecture Principles

Follow Laravel conventions and avoid overengineering. Do not add repository patterns, service layers, microservices, event buses, queues, Redis, React, Vue, or Livewire unless a future requirement needs them.

## Security Baseline

- Never commit `.env` or expose credentials, tokens, or secrets.
- Validate input, distrust client data, and use Laravel CSRF protection.
- Guard against mass assignment; add authorization checks when user/auth features are introduced.
- Use Eloquent or parameter binding for database access.

## Agent Workflow

For non-trivial changes: understand, specify, plan, implement, inspect, test, debug, explain, and improve. Before reporting completion, verify requirements, relevant checks, `git diff`, and that no secrets or obvious security regressions were introduced.

## Development Commands

- `composer run dev` — start Laravel, queue listener, and Vite development processes.
- `php artisan about` — inspect application configuration.
- `php artisan migrate` — apply pending migrations after configuring local MySQL credentials.
