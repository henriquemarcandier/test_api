# Payment Requests API

Laravel 12 RESTful API for multi-currency payment request management with Sanctum authentication, exchange rate integration, auto-expiration, and a full web interface with Tailwind CSS.

## Architecture

- **Laravel 12** + **PHP 8.2+**
- **Sanctum** for API token authentication
- **Session-based auth** for web interface
- **Service Layer** (`ExchangeRateService`) for external API integration (ExchangeRate-API v6)
- **API Resources** for consistent JSON responses (`PaymentRequestResource`)
- **Form Requests** for validation (`StorePaymentRequestRequest`, `ApproveRejectPaymentRequestRequest`)
- **Policy** for authorization (finance-only approve/reject)
- **Gate** for web route authorization (`finance` gate)
- **Enum** for payment status (`PaymentStatus`: pending, approved, rejected, expired)
- **Commands** for scheduled expiration (`payments:expire`)
- **Seeders** with 6 employees + 1 finance user (multiple countries/currencies)
- **Blade** views with Tailwind CSS (CDN) and glassmorphism design
- **RefreshDatabase** trait on all tests

## Requirements

- PHP 8.2+
- MySQL / MariaDB
- Composer
- Extensions: JSON, PDO, MBString, XML, Ctype, cURL

## Setup

```bash
# Enter the project
cd test_api

# Install dependencies
composer install

# Configure environment
cp .env.example .env
# Edit .env with your database credentials

# Option A — Import the provided dump (includes data):
mysql -u root -p test_api < test_api.sql

# Option B — Fresh setup (empty database):
mysql -u root -e "CREATE DATABASE test_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed

# Start the server
php artisan serve
```

> A dump of the database with all migrations and seeded users is available at `test_api.sql` in the project root.

## Web Interface

Access the web UI at `http://localhost/test_api/public/login`.

### Pages

| Page      | Route          | Description                          |
|-----------|----------------|--------------------------------------|
| Login     | `/login`       | Authenticate with email + password   |
| Dashboard | `/dashboard`   | Welcome + currency converter         |
| Users     | `/users`       | List users (paginated)               |
| Payment   | `/payment`     | List payment requests (filterable)   |

### Features

- **Glassmorphism** login card with user list
- **Remember me** option on login
- **Currency converter** on dashboard with swap button and live rate display (12 currencies: EUR, USD, GBP, JPY, BRL, MXN, CAD, AUD, CHF, CNY, INR, KRW)
- **Role-based actions**: finance sees all Edit/Delete buttons; employees see buttons only on their own rows
- **Add User** (finance only): modal with name, email, password, role, country, currency
- **Edit User**: modal with same fields (password optional); finance can edit anyone, employees only themselves
- **Delete User**: confirmation modal with 403 enforcement; finance can delete anyone, employees only themselves
- **Add Payment** (finance only): modal with user select, amount, description, currency (auto-fetches live exchange rate)
- **Edit Payment**: modal for amount/currency/description; only for pending requests; finance edits any, employees only their own
- **Delete Payment**: confirmation modal; only for pending requests (otherwise blocked)
- **Payment totals dashboard**: sum of amount_eur by status (approved / pending / rejected / expired) displayed on payment page
- **Status filter tabs** on payment page: All / Pending / Approved / Rejected / Expired
- **Pagination** with query string preservation on all list pages
- **Icon buttons** with CSS tooltip balloons (pencil for edit, trash for delete, with arrow)
- **Footer**: © 2026 - Powered by Henrique Marcandier Marques Gonçalves
- **Logout** with session invalidation and token regeneration

### Authorization

| Action               | Finance | Employee (own) | Employee (other) |
|----------------------|:-------:|:--------------:|:----------------:|
| View all users       | ✓       | ✓              | ✓                |
| Create user          | ✓       | ✗              | ✗                |
| Edit user            | ✓       | ✓              | ✗                |
| Delete user          | ✓       | ✓              | ✗                |
| Create payment       | ✓       | ✗              | ✗                |
| Edit payment (pending)| ✓      | ✓              | ✗                |
| Delete payment       | ✓       | ✓              | ✗                |
| Approve/reject       | ✓       | ✗              | ✗                |

## API Endpoints

### Authentication

| Method | Endpoint        | Description         | Auth Required |
|--------|-----------------|---------------------|:---:|
| POST   | `/api/register` | Register a new user |  ×  |
| POST   | `/api/login`    | Login               |  ×  |
| POST   | `/api/logout`   | Logout              |  ✓  |
| GET    | `/api/user`     | Get current user    |  ✓  |

### Payment Requests

| Method | Endpoint                                    | Description            | Auth Required |
|--------|---------------------------------------------|------------------------|:---:|
| GET    | `/api/payment-requests`                     | List requests          |  ✓  |
| POST   | `/api/payment-requests`                     | Create a request       |  ✓  |
| GET    | `/api/payment-requests/{id}`                | Get request details    |  ✓  |
| GET/PATCH | `/api/payment-requests/{id}/approve`     | Approve (finance only) |  ✓  |
| GET/PATCH | `/api/payment-requests/{id}/reject`      | Reject (finance only)  |  ✓  |

> Approve/reject endpoints accept both GET and PATCH. GET returns request details; PATCH performs the action.

### Filtering

List requests can be filtered by status (finance only):

```
GET /api/payment-requests?status=pending
GET /api/payment-requests?status=approved
GET /api/payment-requests?status=rejected
GET /api/payment-requests?status=expired
```

Employees automatically see only their own requests regardless of filter.

### API Response Format

```json
{
  "id": 1,
  "amount_local": 5000,
  "currency_code": "BRL",
  "amount_eur": 800,
  "exchange_rate": 6.25,
  "status": "pending",
  "reason": null,
  "expires_at": "2026-06-16T12:00:00.000000Z",
  "created_at": "2026-06-14T12:00:00.000000Z",
  "user": {
    "id": 1,
    "name": "João Silva"
  },
  "approved_by": null,
  "approved_at": null
}
```

> The `user` object is only included for finance users. Employees see `user: null` on other users' requests.

### Examples

```bash
# Register
curl -X POST http://localhost/test_api/public/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@test.com","password":"password123"}'

# Login
curl -X POST http://localhost/test_api/public/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@test.com","password":"password123"}'

# Create payment request (use token from login)
curl -X POST http://localhost/test_api/public/api/payment-requests \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"amount_local":5000,"currency_code":"BRL"}'

# List payment requests
curl http://localhost/test_api/public/api/payment-requests \
  -H "Authorization: Bearer TOKEN"

# Filter by status
curl "http://localhost/test_api/public/api/payment-requests?status=pending" \
  -H "Authorization: Bearer TOKEN"

# Approve (finance only)
curl -X PATCH http://localhost/test_api/public/api/payment-requests/1/approve \
  -H "Authorization: Bearer TOKEN"

# Reject (finance only)
curl -X PATCH http://localhost/test_api/public/api/payment-requests/1/reject \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"reason":"Invalid invoice"}'

# Get current user
curl http://localhost/test_api/public/api/user \
  -H "Authorization: Bearer TOKEN"

# Logout
curl -X POST http://localhost/test_api/public/api/logout \
  -H "Authorization: Bearer TOKEN"
```

## Exchange Rate Integration

The API fetches live EUR → target currency rates from **ExchangeRate-API v6** on each payment request creation. The rate is **persisted immutably** with the request — future rate changes do NOT affect existing records.

**Service:** `app/Services/ExchangeRateService.php`

**API Key:** Configured via `EXCHANGE_RATE_API_KEY` in `.env` (default: `ba0f7ae2e285c305b038a4fd`)

**Conversion formula:** `amount_eur = amount_local / exchange_rate`

**Persisted exchange rate data:**
- `exchange_rate` — the rate at creation time
- `exchange_rate_source` — API URL used
- `exchange_rate_fetched_at` — timestamp when rate was fetched

**Example conversion (5000 BRL → EUR):**
```json
{
  "id": 1,
  "amount_local": 5000,
  "currency_code": "BRL",
  "exchange_rate": 6.25,
  "amount_eur": 800,
  "status": "pending",
  "exchange_rate_source": "https://v6.exchangerate-api.com/v6/KEY/latest/EUR",
  "exchange_rate_fetched_at": "2026-06-14T12:00:00.000000Z"
}
```

## Automated Expiration

Pending requests **expire after 48 hours** via a scheduled command:

```bash
php artisan payments:expire
```

The command marks all pending requests older than 48 hours as `expired`.

It runs every minute via Laravel's scheduler (defined in `routes/console.php`):

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Alternatively, a web-based cron trigger is available at `public/cron.php` (bootstraps Laravel internally, no `exec()` dependency).

## Seeded Users

| Name            | Email                | Role     | Country       | Currency | Password |
|-----------------|----------------------|----------|---------------|----------|----------|
| João Silva      | joao@empresa.com     | employee | Brasil        | BRL      | password |
| John Smith      | john@empresa.com     | employee | EUA           | USD      | password |
| Pierre Dubois   | pierre@empresa.com   | employee | França        | EUR      | password |
| Akira Tanaka    | akira@empresa.com    | employee | Japão         | JPY      | password |
| Carlos Garcia   | carlos@empresa.com   | employee | México        | MXN      | password |
| Sarah Johnson   | sarah@empresa.com    | employee | Reino Unido   | GBP      | password |
| Finance Team    | finance@empresa.com  | finance  | França        | EUR      | password |

## Tests

```bash
php artisan test
```

### Test suites

| Suite                 | Tests                                                     |
|-----------------------|-----------------------------------------------------------|
| AuthTest              | register, login, invalid login, logout                    |
| PaymentRequestTest    | create, list, view, own-request filter, HTTP fake for API |
| FinanceApprovalTest   | approve, reject, employee cannot approve                  |
| ExpirationTest        | old pending requests are expired, recent ones are not     |

All tests use `RefreshDatabase` and `Http::fake()` to avoid external API calls.

## Project Structure

```
app/
├── Console/Commands/
│   └── ExpirePaymentRequests.php          # payments:expire command
├── Enums/
│   └── PaymentStatus.php                  # pending, approved, rejected, expired
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php             # API register/login/logout/user
│   │   ├── LoginWebController.php         # Web login, dashboard, currency converter
│   │   ├── PaymentRequestController.php   # API CRUD + approve/reject
│   │   ├── UsersController.php            # Web user CRUD
│   │   └── WebPaymentController.php       # Web payment CRUD
│   ├── Requests/
│   │   ├── StorePaymentRequestRequest.php
│   │   └── ApproveRejectPaymentRequestRequest.php
│   └── Resources/
│       └── PaymentRequestResource.php     # JSON transformation
├── Models/
│   ├── User.php                           # isFinance(), paymentRequests()
│   └── PaymentRequest.php                 # user(), approver()
├── Policies/
│   └── PaymentRequestPolicy.php           # approve/reject gates
├── Providers/
│   └── AppServiceProvider.php             # finance gate registration
└── Services/
    └── ExchangeRateService.php            # ExchangeRate-API v6 integration

database/
├── factories/
│   ├── UserFactory.php
│   └── PaymentRequestFactory.php
├── migrations/
└── seeders/
    └── DatabaseSeeder.php                 # 7 users across 6 countries

resources/views/
├── login.blade.php                        # Glassmorphism login
├── dashboard.blade.php                    # Currency converter
├── users.blade.php                        # User management
├── payment.blade.php                      # Payment management + totals
└── welcome.blade.php

routes/
├── api.php                                # Sanctum-protected API routes
├── web.php                                # Session-based web routes
└── console.php                            # Schedule configuration

tests/
├── Feature/
│   ├── AuthTest.php
│   ├── PaymentRequestTest.php
│   └── FinanceApprovalTest.php
└── Unit/
    └── ExpirationTest.php

public/
├── index.php                              # Laravel entry point
└── .htaccess
```
