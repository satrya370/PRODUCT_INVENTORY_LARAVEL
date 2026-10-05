# Project Overview

This is a Laravel learning project for **TourFlow**, an operations app for small tour operators: managing Tours, Departures, Guides, Bookings, and Guests.

## Learning Goal

Learn web development with Laravel using an AI-native workflow. AI may generate code, but each change must be explainable, reviewable, testable, and verifiable.

## Technology Stack

- Laravel, PHP, MySQL, Blade, Tailwind CSS, and Vite
- Flowbite or other pre-built Tailwind components may be considered later
- Docker and AWS (ECS Fargate, ECR, RDS MySQL, Secrets Manager) are planned for the deployment stage

## Planned Core Feature

Core domain: `Tour` has many `Departure` (date/time, capacity, assigned Guide) → `Departure` has many `Booking` (guest count, status, capacity validation) → `Booking` has `Guest`; plus `Guide` with availability status. Target features: authentication, dashboard, Tours/Departures/Guides/Bookings list-detail-create-edit-archive, and business rules (capacity, schedule conflict). Do not implement features yet unless asked.

## Architecture Principles

Follow Laravel conventions and avoid overengineering. Do not add repository patterns, service layers, microservices, event buses, queues, Redis, React, Vue, or Livewire unless a future requirement needs them.

## Security Baseline

- Never commit `.env` or expose credentials, tokens, or secrets.
- Validate input, distrust client data, and use Laravel CSRF protection.
- Guard against mass assignment; add authorization checks when user/auth features are introduced.
- Use Eloquent or parameter binding for database access.
- Use escaped Blade output (`{{ }}`) for untrusted data; keep `APP_DEBUG=false` in production.

## Agent Workflow

For non-trivial changes: understand, specify, plan, implement, inspect, test, debug, explain, and improve. Before reporting completion, verify requirements, relevant checks, `git diff`, and that no secrets or obvious security regressions were introduced.

## Development Commands

- `composer run dev` — start Laravel, queue listener, and Vite development processes.
- `php artisan about` — inspect application configuration.
- `php artisan migrate` — apply pending migrations after configuring local MySQL credentials.
