<p align="center">
    <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
    <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
</p>

<h1 align="center">🦅 Eagles Without Borders — Membership Management System</h1>

<p align="center">
    A membership management platform for the Eagles Without Borders organization.
    Manages regions, clubs, members, positions, certificates, and yearly payments with role-based access control.
</p>

---

## ✨ Features

### 📋 Member Management
- **Full CRUD** with profile pictures (auto-optimized to WebP), certificates, and contact info
- **Payment-driven status system** — members are automatically marked active/inactive based on yearly payments
- **CSV Import/Export** with duplicate detection and payment history included
- **Recycle Bin** — soft-delete with restore and permanent delete options
- **Public member profiles** with role-based visibility rules

### 🏛️ Organizational Hierarchy
- **Regions** → **Clubs** → **Members** — full nested structure
- **Positions** with member counts (National President, Club President, Member, etc.)
- Only one **National President** allowed (no club assignment)

### 🔐 Role-Based Access Control (4-tier)
| Role | Scope | Capabilities |
|------|-------|-------------|
| **Super Admin** | Global | Full access to everything, manage other admins, audit logs |
| **National Admin** | Global | Regions, Clubs, Positions, Members, Audit Logs |
| **Regional Admin** | Their region | Members and Clubs within their region |
| **Club Admin** | Their club | Members within their club only |

### 💳 Payment System
- Record yearly membership payments per member
- Auto-updates member status (active/inactive) based on payment history
- Year rollover scheduler — automatically inactivates unpaid members on Jan 1
- Duplicate payment prevention (one payment per member per year)
- Audit trail for all payment actions

### 📊 Dashboard & Reporting
- Role-scoped dashboard with stats (total members, active/inactive breakdown)
- Club membership status with visual progress bars
- Position distribution overview
- Recent members list
- Quick action links

### 🛡️ Security & Auditing
- Full activity logging via Spatie Activitylog
- Role-scoped middleware prevents unauthorized access
- Dark mode support
- Email verification & password confirmation

---

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **Laravel 11** | PHP framework |
| **PHP 8.3** | Runtime |
| **Tailwind CSS 3** | Styling & UI |
| **Alpine.js 3** | Frontend interactivity |
| **Spatie Laravel Permission** | RBAC (4 roles, 17 permissions) |
| **Spatie Activitylog** | Audit trail |
| **Intervention Image** | Image optimization |
| **Vite** | Asset bundling |
| **SQLite / MySQL** | Database |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm

### Installation

```bash
# Clone the repository
git clone https://github.com/your-username/eagles-without-borders.git
cd eagles-without-borders

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seed the database
php artisan migrate --seed

# Build frontend assets
npm run build
```

### Default Admin Accounts (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@example.com | password |
| National Admin | nationaladmin@example.com | password |
| Regional Admin | regionaladmin@example.com | password |
| Club Admin (Roxas) | ca.roxas@example.com | password |

### Development Server

```bash
php artisan serve
npm run dev
```

### Year Rollover Simulation

```bash
# Preview which members would be inactivated
php artisan members:simulate-rollover

# Process the rollover for real
php artisan members:process-year-rollover --force
```

---

## 📁 Project Structure

```
app/
├── Console/Commands/       # Artisan commands (rollover, seed, etc.)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin CRUD controllers
│   │   └── Auth/           # Authentication controllers
│   ├── Middleware/          # ScopeMiddleware for RBAC
│   └── Requests/           # Form validation requests
├── Models/                 # Eloquent models
└── View/Components/        # Blade components

database/
├── migrations/             # Database schema
└── seeders/                # Test data (50 members, payments, etc.)

resources/views/
├── admin/                  # Admin dashboard & CRUD views
├── auth/                   # Login & authentication views
├── components/             # Reusable Blade components
├── layouts/                # App layout with sidebar & navbar
└── public/                 # Public member profile pages
```

---

## 📊 Database Schema

```
regions ──── clubs ──── members ──── certificates
                  │                      │
                  │                 payments
                  │
             positions
```

- `members` supports soft-deletes, as do `certificates` and `payments`
- `payments` has a unique constraint on `(member_id, year_paid)`
- `club_id` on `members` is nullable (for National President)

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
