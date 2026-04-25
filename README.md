# WashFlow

WashFlow is a Laravel-based Laundry Management System for small businesses.

## 1. System Architecture Overview

- Backend: Laravel 13, Eloquent ORM, middleware-based role control
- Frontend: Blade templates + Tailwind CSS
- Auth: Laravel Breeze
- Database: MySQL-compatible schema (works with local MySQL, Railway MySQL, PlanetScale)
- Modules:
	- Customer Booking
	- Order Tracking
	- Payments (manual)
	- Inventory Monitoring
	- Transactions
	- Reports (table + CSV export)

Flow summary:
- Customer creates booking
- Staff/Admin converts booking to order
- Staff updates order status across laundry stages
- Inventory is auto-deducted on washing stage
- Transaction is auto-recorded when order is completed
- Payment is recorded manually by staff/admin

## 2. Folder Structure

Key project folders and files:

- app/Models: User, Booking, Order, Payment, Transaction, Inventory
- app/Http/Controllers: BookingController, OrderController, PaymentController, InventoryController, ReportController, DashboardController, CustomerPortalController
- app/Http/Middleware/EnsureRole.php
- database/migrations: users + all WashFlow module tables
- resources/views:
	- dashboard.blade.php
	- bookings/*
	- orders/*
	- payments/*
	- inventories/*
	- reports/index.blade.php
	- customer/portal.blade.php
- routes/web.php
- bootstrap/app.php (middleware alias registration)

## 3. Database Schema

### users
- id
- name
- email
- role (admin, staff, customer)
- password
- remember_token
- timestamps

### bookings
- id
- user_id (FK users)
- service_type
- scheduled_at
- notes
- status
- timestamps

### orders
- id
- booking_id (FK bookings)
- user_id (FK users)
- status (pending, picked-up, washing, drying, ready, completed)
- weight_kg
- unit_price
- total_cost
- inventory_deduction_json
- timestamps

### transactions
- id
- order_id (unique FK orders)
- user_id (FK users)
- amount
- service_summary
- completed_at
- timestamps

### payments
- id
- order_id (FK orders)
- user_id (FK users)
- payment_method (cash, gcash/manual)
- payment_status (paid, unpaid)
- amount
- paid_at
- reference
- timestamps

### inventories
- id
- name (unique)
- quantity
- unit
- low_stock_threshold
- timestamps

## 4. Key Laravel Commands

Install dependencies:

```bash
composer install
npm install
```

Setup app:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Run locally:

```bash
php artisan serve
npm run dev
```

Clear caches (production troubleshooting):

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

## 5. Sample Controller Code

Snippet from Order status handling (completed order creates transaction automatically):

```php
if ($validated['status'] === 'completed') {
		$order->booking->update(['status' => 'completed']);

		Transaction::updateOrCreate(
				['order_id' => $order->id],
				[
						'user_id' => $order->user_id,
						'amount' => $order->total_cost,
						'service_summary' => $order->booking->service_type,
						'completed_at' => now(),
				]
		);
}
```

Implemented in app/Http/Controllers/OrderController.php.

## 6. Route Definitions

Main routes are defined in routes/web.php.

- Public:
	- GET / (welcome)
- Authenticated:
	- GET /dashboard
	- profile routes
- Customer only:
	- bookings resource
	- GET /portal
- Admin/Staff only:
	- orders resource
	- payments resource
	- inventories resource
	- GET /reports
	- GET /reports/export/csv

## 7. Middleware Setup

Custom middleware:
- app/Http/Middleware/EnsureRole.php

Alias registration:
- bootstrap/app.php

```php
$middleware->alias([
		'role' => EnsureRole::class,
]);
```

Usage example in routes:

```php
Route::middleware('role:admin,staff')->group(function () {
		Route::resource('orders', OrderController::class);
});
```

## 8. Deployment Guide (Render or Railway)

### A. Push to GitHub

```bash
git init
git add .
git commit -m "Initial WashFlow implementation"
git branch -M main
git remote add origin https://github.com/<username>/washflow.git
git push -u origin main
```

### B. Deploy on Render (free-friendly)

1. Create a new Web Service from your GitHub repo.
2. Set build command:
	 - composer install --no-dev --optimize-autoloader
	 - npm install
	 - npm run build
3. Set start command:
	 - php artisan serve --host=0.0.0.0 --port=$PORT
4. Add environment variables:
	 - APP_NAME=WashFlow
	 - APP_ENV=production
	 - APP_DEBUG=false
	 - APP_KEY=(run php artisan key:generate locally and copy)
	 - APP_URL=https://your-render-url.onrender.com
	 - DB_CONNECTION=mysql
	 - DB_HOST=...
	 - DB_PORT=3306
	 - DB_DATABASE=...
	 - DB_USERNAME=...
	 - DB_PASSWORD=...
5. After first deploy, open Render shell and run:
	 - php artisan migrate --force
	 - php artisan db:seed --force

### C. Deploy on Railway

1. Create a new Railway project from GitHub repo.
2. Add MySQL plugin (or connect external PlanetScale).
3. Configure environment variables as listed above.
4. Add build and start commands similarly.
5. Run migrations:
	 - php artisan migrate --force
	 - php artisan db:seed --force

### D. PlanetScale MySQL Compatibility

- Use DB_CONNECTION=mysql and PlanetScale credentials.
- Ensure SSL settings if required by provider.
- Avoid unsupported MySQL features (schema here is PlanetScale-friendly).

### E. .env Production Notes

- Set APP_ENV=production
- Set APP_DEBUG=false
- Configure SESSION_DRIVER=database or file
- Configure CACHE_STORE=file or database
- Run php artisan config:cache after env setup

## 9. Future Improvements

- Add status timeline UI with auto-refresh interval
- Add printable invoice and receipt PDF
- Add advanced inventory usage analytics
- Add audit trail for staff actions
- Add unit and feature tests for order lifecycle and role middleware

## Bonus Ideas

- SMS notification integration via free-tier providers
- QR code order pickup tracking
- Flutter mobile app consuming Laravel API
