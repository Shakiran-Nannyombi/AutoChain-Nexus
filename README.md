# Autochain Nexus Inventory Management System

A modern, multi-role inventory management system for the automotive supply chain, built with Laravel 12, Vite, and Tailwind CSS. The system supports multiple user roles (Manufacturer, Supplier, Vendor, Retailer, Analyst, Admin), custom registration with document uploads, and a visually appealing, responsive UI.

---

## Project Structure

```
Inventory-Mgt-System-25/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/                # Authentication controllers (login, register, password reset)
│   │   ├── Middleware/              # Custom and default middleware
│   │   ├── Requests/                # Form request validation (e.g., LoginRequest)
│   │   └── Kernel.php               # HTTP kernel
│   ├── Models/                      # Eloquent models for all user roles
│   ├── Providers/                   # App and route service providers
│   └── ...                          # Other Laravel app folders
│
├── bootstrap/                       # Laravel bootstrap files
├── config/                          # Configuration files (app, auth, database, etc.)
├── database/
│   ├── migrations/                  # All migration files (users, roles, custom tables)
│   ├── seeders/                     # Database seeders (admin, test users)
│   └── factories/                   # Model factories (if any)
│
├── public/
│   ├── images/                      # Logo and car images for the landing page
│   └── ...                          # Public assets
│
├── resources/
│   ├── css/                         # Tailwind and custom CSS (welcome.css, auth.css, app.css)
│   ├── js/                          # JS files for Vite (welcome.js, auth.js, app.js, bootstrap.js)
│   └── views/
│       ├── auth/                    # Auth pages (login, register, admin-login, etc.)
│       ├── layouts/                 # Layouts for dashboards, etc.
│       ├── dashboards/              # Custom dashboards for roles (if any)
│       └── welcome.blade.php        # Custom landing page
│
├── routes/
│   ├── web.php                      # Web routes
│   ├── api.php                      # API routes (if any)
│   └── ...                          # Other route files
│
├── .env                             # Environment configuration
├── composer.json                    # PHP dependencies
├── package.json                     # JS dependencies
├── vite.config.js                   # Vite configuration
├── tailwind.config.js               # Tailwind configuration
├── README.md                        # Project documentation
└── ...
```

---

## Setup Instructions

### 1. Prerequisites
- PHP 8.2+ (recommended 8.3+)
- Composer
- Node.js & npm
- XAMPP (for Apache & MySQL)

### 2. Installation Steps

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd Inventory-Mgt-System-25
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JS dependencies**
   ```bash
   npm install
   ```

4. **Copy and configure environment file**
   ```bash
   cp .env.example .env
   ```
   - Set your database credentials in `.env`:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=inventory_system
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   - For development (hot reload):
     ```bash
     npm run dev
     ```
   - For production:
     ```bash
     npm run build
     ```

8. **Start the Laravel server**
   ```bash
   php artisan serve
   ```
   - Visit [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## Default Users

- **Admin**
  - Email: `admin@autochain.com`
  - Password: `admin123`
- **Test User**
  - Email: `test@example.com`
  - Password: (set by factory, check seeder)

---

## Features

- Multi-role authentication and registration
- File uploads for registration (documents, profile picture)
- Custom dashboards and layouts for each user role
- Application status checking
- Admin management
- Responsive, modern UI (Tailwind CSS, custom CSS)
- Vite-powered asset bundling
- Database seeding for quick setup

---

## Notes

- All landing page images are in `public/images/`
- "Back to Home" links are present on login and register pages
- Vite and Tailwind are fully configured for easy asset management
- The project is ready for further customization and development
