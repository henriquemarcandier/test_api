# Payment Requests API

Laravel 12 RESTful API for multi-currency payment request management with Sanctum authentication, exchange rate integration, auto-expiration, and a full web interface with Tailwind CSS.

## Architecture

- **Laravel 12** + **PHP 8.2+**
- **Sanctum** for API token authentication
- **Service Layer** (`ExchangeRateService`) for external API integration
- **API Resources** for consistent JSON responses
- **Form Requests** for validation
- **Policy** for authorization (finance-only approval)
- **Gate** for web route authorization (finance CRUD)
- **Enum** for payment status
- **Commands** for scheduled expiration
- **Seeders** with 6 employees + finance user
- **Blade** views with Tailwind CSS (CDN)

## Requirements

- PHP 8.2+
- MySQL / MariaDB
- Composer

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

| Page        | Route            | Description                          |
|-------------|------------------|--------------------------------------|
| Login       | `/login`         | Authenticate with email + password   |
| Dashboard   | `/dashboard`     | Welcome + currency converter         |
| Users       | `/users`         | List all users (paginated)           |
| Payment     | `/payment`       | List payment requests (filterable)   |

### Features

- **Glassmorphism** login card with user list
- **Currency converter** on dashboard with swap button and live rate display
- **Role-based actions**: finance sees all Edit/Delete buttons; employees see buttons only on their own rows
- **Add User** (finance only): modal with name, email, password, role, country, currency
- **Edit User**: modal with same fields (password optional); finance can edit anyone, employees only themselves
- **Delete User**: confirmation modal; finance can delete anyone, employees only themselves
- **Add Payment** (finance only): modal with user select, amount, currency, description
- **Edit Payment**: modal for amount/currency/description; only for pending requests; finance edits any, employees only their own
- **Delete Payment**: confirmation modal; only for pending requests
- **Icon buttons** with CSS tooltip balloons (pencil for edit, trash for delete, with arrow)
- **Status filter tabs** on payment page: All / Pending / Approved / Rejected
- **Footer**: © 2026 - Powered by Henrique Marcandier Marques Gonçalves

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
```

## Exchange Rate Integration

The API fetches live EUR → target currency rates from ExchangeRate-API v6 on each payment request creation. The rate is **persisted immutably** with the request — future rate changes do NOT affect existing records.

**Service:** `app/Services/ExchangeRateService.php`

**Example conversion (5000 BRL → EUR):**
```json
{
  "id": 1,
  "amount_local": 5000,
  "currency_code": "BRL",
  "exchange_rate": 6.25,
  "amount_eur": 800,
  "status": "pending"
}
```

## Automated Expiration

Pending requests expire after 48 hours via a scheduled command:

```bash
php artisan payments:expire
```

Runs hourly via Laravel scheduler:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Seeded Users

| Name            | Email                | Role    | Country       | Currency |
|-----------------|----------------------|---------|---------------|----------|
| João Silva      | joao@empresa.com     | employee| Brazil        | BRL      |
| John Smith      | john@empresa.com     | employee| United States | USD      |
| Pierre Dubois   | pierre@empresa.com   | employee| France        | EUR      |
| Akira Tanaka    | akira@empresa.com    | employee| Japan         | JPY      |
| Carlos Garcia   | carlos@empresa.com   | employee| Mexico        | MXN      |
| Sarah Johnson   | sarah@empresa.com    | employee| United Kingdom| GBP      |
| Finance Team    | finance@empresa.com  | finance | Ireland       | EUR      |

All users password: `password`

## Tests

```bash
php artisan test
```

### Test suites

| Suite                 | Tests                                  |
|-----------------------|----------------------------------------|
| AuthTest              | register, login, invalid login, logout |
| PaymentRequestTest    | create, list, view, own-request filter |
| FinanceApprovalTest   | approve, reject, employee cannot       |
| ExpirationTest        | old pending requests are expired       |

## Project Structure

```
app/
├── Console/Commands/
│   └── ExpirePaymentRequests.php
├── Enums/
│   └── PaymentStatus.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── LoginWebController.php
│   │   ├── PaymentRequestController.php
│   │   ├── UsersController.php
│   │   └── WebPaymentController.php
│   ├── Requests/
│   │   ├── StorePaymentRequestRequest.php
│   │   └── ApproveRejectPaymentRequestRequest.php
│   └── Resources/
│       └── PaymentRequestResource.php
├── Models/
│   ├── User.php
│   └── PaymentRequest.php
├── Policies/
│   └── PaymentRequestPolicy.php
├── Providers/
│   └── AppServiceProvider.php  (finance gate)
└── Services/
    └── ExchangeRateService.php

database/
├── factories/
│   ├── UserFactory.php
│   └── PaymentRequestFactory.php
├── migrations/
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── dashboard.blade.php
├── login.blade.php
├── payment.blade.php
└── users.blade.php

routes/
├── api.php
├── console.php
└── web.php

tests/
├── Feature/
│   ├── AuthTest.php
│   ├── PaymentRequestTest.php
│   └── FinanceApprovalTest.php
└── Unit/
    └── ExpirationTest.php
```
