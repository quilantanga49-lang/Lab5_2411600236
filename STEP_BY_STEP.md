# Step-by-Step Guide — Sari-Sari Store Inventory Management System

Laravel MVC implementation of Laboratory Exercise 5, adapted from the hardware-store theme to a **sari-sari store** as requested.

**Project folder:** `C:\xampp\htdocs\Lab5_2411600236`  
**Database:** `sari_sari_inventory` on XAMPP MySQL (user `root`, empty password)  
**Default login:** `admin@sari.com` / `password`

---

## 0. Environment used

| Item | Value / location |
|---|---|
| XAMPP | `C:\xampp` (PHP 8.5.13, MariaDB 10.11.19) |
| PHP CLI | `C:\xampp\php\php.exe` |
| Composer | `C:\xampp\php\composer.phar` |
| Project | `C:\xampp\htdocs\Lab5_2411600236` |
| Editor | Visual Studio Code (`code C:\xampp\htdocs\Lab5_2411600236`) |
| App URL | `http://127.0.0.1:8000` |

> **Note about this machine:** Windows Application Control blocks some XAMPP DLLs (`php_curl.dll`, `php_mbstring.dll`, Apache `mod_auth_basic.so`). The project still works because:
>
> - `symfony/polyfill-mbstring` supplies the missing `mb_*` functions (plus a small fallback helper in `app/Support/helpers.php`).
> - Composer falls back to PHP streams and the bundled `unzip.cmd` wrapper.
> - Apache is not used; run the app with `php artisan serve` instead.

---

## Part 1 — Laravel environment setup

### Step 1: Install / verify Composer

Composer was installed as `C:\xampp\php\composer.phar`.

```powershell
& C:\xampp\php\php.exe C:\xampp\php\composer.phar --version
```

### Step 2: Create the Laravel project

```powershell
cd C:\xampp\htdocs
$env:Path = 'C:\xampp\php;' + $env:Path
C:\xampp\php\php.exe C:\xampp\php\composer.phar create-project laravel/laravel Lab5_2411600236 --ignore-platform-req=ext-mbstring
cd Lab5_2411600236
```

### Step 3: Configure the database

1. Start XAMPP MySQL (already running on port 3306).
2. Create the database:

```powershell
& C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS sari_sari_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

3. Update `.env`:

```dotenv
APP_NAME="Sari-Sari Store Inventory"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sari_sari_inventory
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

4. Verify MySQL from phpMyAdmin: `http://localhost/phpmyadmin` → database `sari_sari_inventory`.

---

## Part 2 — Authentication (Laravel Breeze)

### Step 1: Install Breeze

```powershell
C:\xampp\php\php.exe C:\xampp\php\composer.phar require --dev laravel/breeze --ignore-platform-req=ext-mbstring
C:\xampp\php\php.exe artisan breeze:install blade --no-interaction
```

The npm step failed because Node is not installed, but the Blade scaffolding was installed. The views were then rewritten to use **Bootstrap CDN + custom CSS** instead of Vite/Tailwind, so no build step is required.

### Step 2: Create the test user

The user is created by `DatabaseSeeder`:

```powershell
C:\xampp\php\php.exe artisan db:seed --force
```

Login: **admin@sari.com** / **password** (password stored with `Hash::make()`).

Seeded demo accounts (all password: **password**):

| Name | Email | Role | Email Verified |
|---|---|---|---|
| Store Manager | admin@sari.com | manager | Yes |
| Maria Cashier | cashier@sari.com | staff | Yes |
| Juan Helper | helper@sari.com | staff | No |

### Step 3: Apply the color scheme to auth views

Files edited:

- `resources/views/layouts/guest.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/confirm-password.blade.php`
- `resources/views/auth/verify-email.blade.php`
- `public/css/app.css`

Palette: amber/orange `#f28c28`, green `#2e7d32`, cream `#fff8ef`, dark brown `#4a2f1d`.

---

## Part 3 — Product model and migration

### Step 1: Generate model + migration (already created)

```powershell
C:\xampp\php\php.exe artisan make:model Product -m
C:\xampp\php\php.exe artisan make:model InventoryTransaction -m
```

Created files:

- `app/Models/Product.php`
- `app/Models/InventoryTransaction.php`
- `database/migrations/2026_11_23_000001_create_products_table.php`
- `database/migrations/2026_11_23_000002_create_inventory_transactions_table.php`

### Step 2: Products table schema

Columns: `id`, `name`, `sku` (unique), `description`, `category`, `quantity`, `reorder_level`, `unit_price`, `supplier`, `timestamps`.

### Step 3: Inventory transactions table

Columns: `id`, `product_id` (FK, cascade delete), `type` (`in`/`out`), `quantity`, `reference`, `user_id` (FK, null on delete), `timestamps`.

### Step 4: Run migrations

```powershell
C:\xampp\php\php.exe artisan migrate:fresh --seed --force
```

### Step 5: Model business logic

`Product.php` includes:

- `$fillable` mass-assignment whitelist
- `transactions()` relationship
- `scopeLowStock()`
- accessors: `is_low_stock`, `is_out_of_stock`, `stock_value`, `status`
- `adjustStock()` which updates quantity **and** writes a transaction

---

## Part 4 — Product controller

File: `app/Http/Controllers/ProductController.php`

| Method | Purpose |
|---|---|
| `index()` | Paginated list + search (name, SKU, category, brand, supplier) and brand/category filters |
| `create()` | Show add-product form |
| `store()` | Validate and insert a product |
| `show()` | Product details + stock history |
| `edit()` | Show pre-filled edit form |
| `update()` | Validate and update a product |
| `destroy()` | Move a product to Trash (soft delete) — manager only |
| `trash()` | Show all soft-deleted products |
| `restore()` | Restore a product from Trash — manager only |
| `forceDelete()` | Permanently delete a trashed product — manager only |
| `adjustStock()` | Record stock-in / stock-out |

Validation rules include required fields, string length limits, numeric prices, minimum quantities, and unique SKU (ignoring the current record on update), with custom messages.

### Soft deletes (temporary Trash)

`app/Models/Product.php` uses Laravel's `SoftDeletes` trait and the `products` table has a `deleted_at` column (migration `2026_11_23_000004_add_deleted_at_to_products_table.php`). Deleting a product sets `deleted_at` instead of removing the row, so it can be restored from **Products → Trash**. Only a manager can delete, restore, or permanently delete.

### Manager / Staff roles

The `users` table has a `role` column (`manager` or `staff`, migration `2026_11_23_000005_add_role_to_users_table.php`). The **Store Manager** dropdown in the navbar lets you switch roles. Managers can see Trash and delete/restore products; Staff can browse and manage stock but cannot delete or restore (server-side checks return HTTP 403).

---

## Part 4B — User management

File: `app/Http/Controllers/UserController.php`

| Method | Purpose |
|---|---|
| `index()` | Paginated, searchable users table (User, Email, Role, Email Verified, Status, Actions) |
| `create()` | Show the Add User form — manager only |
| `store()` | Validate and create the account with a hashed password — manager only |
| `trash()` | Show soft-deleted users with Restore / permanent delete |
| `updateRole()` | Change a user between Manager and Staff |
| `toggleStatus()` | Activate / deactivate an account |
| `destroy()` | Move a user to Trash (soft delete) — manager only |
| `restore()` | Restore a trashed user — manager only |
| `forceDelete()` | Permanently delete a trashed user — manager only |

Users use the `SoftDeletes` trait and an `is_active` boolean (migration `2026_11_23_000006_add_soft_deletes_and_status_to_users_table.php`). The table columns are **User, Email, Role, Email Verified, Status, Actions**. A manager can change roles, toggle status, delete, restore, and permanently delete. Staff see a read-only table and direct action attempts return HTTP 403. You cannot delete or deactivate your own account — a guard redirects with a warning.

The **Users** nav item opens the list. The **Add User** button opens a form with full name, email, role (Manager/Staff), password + confirmation, Email verified, and Active account checkboxes. Validation checks required fields, valid/unique email, minimum 8-character password, and confirmation match. Passwords are stored with `Hash::make()`. Deleted accounts are reached with the **Deleted Users** button on the list page.

---

## Part 5 — Blade views

| File | Purpose |
|---|---|
| `resources/views/layouts/app.blade.php` | Master layout: nav, flash messages, Bootstrap, custom CSS |
| `resources/views/layouts/guest.blade.php` | Login/register/reset layout |
| `resources/views/dashboard.blade.php` | Real statistics, alerts, category bars, recent activity |
| `resources/views/products/index.blade.php` | Product table, search, status badges, actions, brand/category filters |
| `resources/views/products/_form.blade.php` | Shared create/edit form with CSRF and inline errors (includes Brand) |
| `resources/views/products/create.blade.php` | Add product page |
| `resources/views/products/edit.blade.php` | Edit product page |
| `resources/views/products/show.blade.php` | Product details, stock adjustment, transaction history |
| `resources/views/products/trash.blade.php` | Trash page with Restore and permanent-delete buttons |
| `resources/views/catalog/brands.blade.php` | Brand cards with product counts and filter links |
| `resources/views/catalog/categories.blade.php` | Category cards with product counts and filter links |
| `resources/views/users/index.blade.php` | Users table: name, email, role, email verified, status, actions |
| `resources/views/users/create.blade.php` | Add User form with validation errors |
| `resources/views/users/trash.blade.php` | Deleted users with Restore and permanent-delete buttons |

---

## Part 6 — Routes

File: `routes/web.php`

```php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/brands', [CatalogController::class, 'brands'])->name('brands.index');
    Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');

    Route::get('products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    Route::resource('products', ProductController::class);
    Route::post('products/{product}/stock', [ProductController::class, 'adjustStock'])
        ->name('products.stock');
    Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.status');
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::post('/role/switch', [RoleController::class, 'switch'])->name('role.switch');
    // profile routes...
});

require __DIR__.'/auth.php';
```

---

## Part 7 — Dashboard with real data

File: `app/Http/Controllers/DashboardController.php`

Calculates:

- Total products
- Low-stock items (`quantity <= reorder_level`)
- Out-of-stock items (`quantity = 0`)
- Total inventory value (`quantity × unit_price`)
- Recent products and stock movements
- Product counts per category

---

## Part 8 — Seeder

`database/seeders/ProductSeeder.php` inserts **18 sari-sari products** across Beverages, Noodles, Snacks, Canned Goods, Household, Personal Care, and Condiments. Quantities are varied so several items are low stock and one is out of stock.

`DatabaseSeeder.php` creates the manager user, calls `ProductSeeder`, and records an initial `Stock In` transaction for every product.

Run again anytime with:

```powershell
C:\xampp\php\php.exe artisan db:seed --force
```

---

## Part 9 — Theme / Lab Exercise 4 connection

- `public/css/app.css` defines CSS variables and overrides Bootstrap nav, buttons, cards, tables, badges, progress bars, and pagination.
- The same palette is used on both guest (auth) and authenticated pages.
- Navigation includes Dashboard, Products, Add Product, Low Stock, Profile, and Logout.

---

## Part 10 — Testing checklist (verified)

- [x] `php artisan serve` starts at `http://127.0.0.1:8000`
- [x] Login page loads with custom styling
- [x] Login with `admin@sari.com` / `password` works
- [x] Dashboard shows 18 products, 7 low-stock, 1 out-of-stock, ₱16,913.00 value
- [x] Product index lists all products with status badges
- [x] Empty add-product form shows custom validation errors
- [x] Valid add-product form creates a record and shows a success message
- [x] Edit form loads existing data
- [x] Show page displays details and stock history
- [x] Low-stock badges and alerts display
- [x] Logout works
- [x] Responsive Bootstrap layout for mobile

---

## Part 11 — Git / GitHub

Git 2.56.1 was installed and the project is already committed locally:

```powershell
git init -b main
git add .
git commit -m "Lab 5: Laravel MVC sari-sari store inventory system (2411600236)"
```

Commit: `75b2057 Lab 5: Laravel MVC sari-sari store inventory system (2411600236)`

To push, you still need to provide a GitHub repository URL and sign in:

```powershell
git remote add origin <your-repository-url>
git push -u origin main
```

> No GitHub URL or credentials are configured on this machine, so the remote push could not be completed. The local commit is ready.

---

## Running the app

```powershell
cd C:\xampp\htdocs\Lab5_2411600236
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

Open **http://127.0.0.1:8000/login**.

Open the project in VS Code:

```powershell
code C:\xampp\htdocs\Lab5_2411600236
```

## Reset everything

```powershell
C:\xampp\php\php.exe artisan migrate:fresh --seed --force
```

## Screenshots for the lab report

All available screenshots are captured in `screenshots/`:

| Lab screenshot | File |
|---|---|
| 1. VS Code — Product model | `screenshots/01-vscode-product-model.png` |
| 2. VS Code — Product controller | `screenshots/02-vscode-product-controller.png` |
| 3. VS Code — Blade form view | `screenshots/03-vscode-blade-form.png` |
| 4. VS Code — Custom CSS | `screenshots/04-vscode-custom-css.png` |
| 5. Login page | `screenshots/05-login.png` |
| 6. Dashboard with real statistics | `screenshots/06-dashboard.png` |
| 7. Product index page | `screenshots/07-products-index.png` |
| 8. Add product form | `screenshots/08-add-product.png` |
| 9. Product edit page | `screenshots/09-edit-product.png` |
| (extra) Product details / stock history | `screenshots/09b-product-details.png` |
| 10. MySQL products table (CLI fallback) | `screenshots/10-mysql-products-table.png` |
| 11. GitHub repository | `screenshots/11-github-repository.png` |

> Apache/phpMyAdmin is blocked by Windows Application Control, so the products table is shown from the MySQL CLI instead.

All required screenshots are captured. Optional: Terminal — successful Artisan output.

## Lab report PDF

The completed report is `Lab5_2411600236_Quilantanga.pdf`, with an HTML source at `Lab5_2411600236_LabReport.html`.
