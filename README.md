# Luxérie

Laravel storefront and administration prototype for the Luxérie skincare brand.

## Current prototype

- Database-driven homepage, catalog, and product detail pages
- Protected administrator login
- Product and category management
- Inventory and visibility controls
- Responsive Blade/Tailwind interface
- SQLite for zero-configuration local development; migrations are MySQL-compatible

## Local setup

```bash
composer install
npm install
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Prototype administrator credentials:

- Email: `admin@luxerie.test`
- Password: `password`

These credentials are development-only and must be replaced before any deployment.

## Next slices

1. Image uploads and media management
2. Product variants
3. Session cart and checkout domain
4. Orders and PayPal integration
5. MySQL production environment and deployment configuration
