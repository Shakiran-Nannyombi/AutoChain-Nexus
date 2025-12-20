# Autochain Nexus

## Live Car Inventory Management System

![Autochain Nexus Logo](public/images/AutoChainNexus.png)

**🌐 Live System**: [https://autochain-nexus.onrender.com](https://autochain-nexus.onrender.com)

This is a multi-role inventory management system built with Laravel 11, featuring role-based dashboards, automated vendor validation, and cloud-native microservices architecture.

This system serves manufacturers, suppliers, vendors, retailers, analysts, and administrators with tailored interfaces and workflows.

## Deployment Architecture with Production Services

- **Main Application**: [Laravel on Render](https://autochain-nexus.onrender.com)
- **ML/Analytics API**: [FastAPI Service](https://fastapiserviuce.onrender.com)
- **Email Service**: [Spring Boot Email API](https://springbootservice.onrender.com)
- **Document Validator**: [Spring Boot Validator API](https://validator-api-f40v.onrender.com)
- **Database**: SQLite (Attached Disk on Render)
- **Custom Domain**: autochain-nexus.onrender.com

## Key Features of our System

### Multi-Role Authentication System

- **6 User Roles**: Admin, Manufacturer, Supplier, Vendor, Retailer, Analyst
- **Secure Registration**: Multi-step approval process with document upload
- **Role-Based Access Control**: Each role has specific permissions and dashboard access
- **Profile Management**: Users can update their information and profile photos
- **Password Reset**: Secure email-based password reset functionality

### Admin Dashboard

- **User Management**: Approve/reject new user applications
- **System Analytics**: Real-time charts and metrics
- **Global Search**: Search across users and dashboard pages
- **Scheduled Reports**: Automated email report generation
- **Database Backups**: Automated backup management
- **System Settings**: Configure validation rules and criteria
- **Facility Visits**: Schedule and track on-site validations
- **Communications**: Internal messaging system
- **User Activity Monitoring**: Track user logins and activity

### Manufacturer Dashboard

- **Inventory Overview**: Real-time stock levels and alerts
- **Production Planning**: Demand prediction and capacity planning
- **Quality Control**: Checklists and quality assurance tools
- **Supplier Management**: Track supplier performance and relationships
- **Analytics**: Production metrics and trend analysis
- **Chat System**: Internal communication platform
- **Document Management**: Upload and manage production documents

### Retailer Dashboard

- **Stock Overview**: Current inventory levels and alerts
- **Sales Updates**: Real-time sales data and trends
- **Order Management**: Place and track orders with suppliers
- **Notifications**: System alerts and updates
- **Chat System**: Communicate with suppliers and vendors
- **Analytics**: Sales performance and inventory turnover

### Analyst Dashboard

- **Data Analytics**: Comprehensive business intelligence
- **Inventory Analysis**: Stock optimization recommendations
- **Market Trends**: Industry analysis and forecasting
- **Performance Metrics**: KPI tracking and reporting
- **Predictive Analytics**: Demand forecasting models
- **Custom Reports**: Generate tailored business reports

### Supplier Dashboard

- **Order Management**: Track incoming orders and fulfillment
- **Inventory Tracking**: Real-time stock levels
- **Delivery History**: Complete delivery tracking
- **Checklist Receipt**: Quality control checklists
- **Chat System**: Customer communication
- **Performance Metrics**: Supplier performance analytics

### Vendor Dashboard

- **Delivery Management**: Track deliveries and schedules
- **Inventory Control**: Stock management and alerts
- **Order Processing**: Handle incoming orders
- **Analytics**: Sales and performance metrics
- **Chat System**: Customer and partner communication
- **Document Management**: Upload delivery documents

## Vendor Onboarding & Approval Flow

The vendor registration and approval process is designed to ensure only qualified vendors gain access to the system. Here's how it works:

1. **Registration**
   - Vendor completes the registration form, uploads required documents, and selects a manufacturer (from approved manufacturers) whose facility they wish to visit.
   - The registration is submitted for admin review.

2. **Validation**
   - Admin reviews the vendor's application and runs automated validation (including document checks and scoring).
   - If the vendor's validation score is **below the threshold**, the admin can explicitly reject the application (triggering a rejection email). The vendor remains pending until rejected.
   - If the vendor's validation score is **above the threshold**, the vendor is **not immediately approved**. Instead:
     - The vendor's status is set to `pending_visit`.
     - The system automatically schedules a facility visit with the selected manufacturer.
     - The vendor receives an email notification that a visit is pending.

3. **Facility Visit**
   - The admin can customize and reschedule the facility visit as needed.
   - After the visit is completed and marked as such by the admin, the vendor's status is updated.

4. **Final Approval**
   - Only after a successful facility visit is the vendor's status set to `approved`.
   - The vendor receives a final approval email and gains access to the system.

**Note:** Other user roles (manufacturer, supplier, retailer, analyst) follow a simpler approval process and do not require a facility visit.

## Cloud-Native Architecture

### Core Technologies

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: PostgreSQL (Supabase Cloud)
- **Asset Bundling**: Vite
- **Authentication**: Laravel Breeze with custom role system
- **Deployment**: Docker containers on Render

### Microservices Architecture

- **Main App**: Laravel (PHP) - User interfaces, business logic
- **ML/Analytics API**: FastAPI (Python) - Demand forecasting, segmentation
- **Email Service**: Spring Boot (Java) - Email notifications
- **Document Validator**: Spring Boot (Java) - PDF processing, validation
- **Database**: Supabase PostgreSQL - Managed cloud database

### Cloud Infrastructure

- **Hosting**: Render (Docker-based deployment)
- **Database**: Supabase (PostgreSQL as a Service)
- **Domain**: Render (laravel-service.onrender.com)
- **SSL**: Automatic Let's Encrypt certificates
- **File Storage**: Render persistent storage
- **Environment**: Production-ready with environment variables

## Prerequisites

Before installation, ensure you have:

- **PHP 8.2** or higher
- **Composer**
- **Node.js** and **npm**
- **Java 17** or higher (for microservices)
- **Maven** (for Spring Boot services)
- **PostgreSQL** database server (or MySQL for local development)
- **Git**

## Local Development Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Jerome-B-Nuwagaba/Inventory-Mgt-System-25
cd Inventory-Mgt-System-25
```

### 2. Laravel Backend Setup

1. **Install PHP Dependencies:**

    ```bash
    composer install
    ```

2. **Environment Configuration:**

    ```bash
    cp .env.example .env
    ```

   Update `.env` with your database credentials:

   ```env
   # For local development (MySQL)
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_system
   DB_USERNAME=root
   DB_PASSWORD=
   
   # For production (PostgreSQL - Supabase)
   DB_CONNECTION=pgsql
   DB_HOST=aws-0-us-east-2.pooler.supabase.com
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres.stgikmmbtzytaxdrxvhc
   DB_PASSWORD=your_supabase_password
   DB_SSLMODE=require
   ```

3. **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

4. **Database Setup:**

    ```bash
    php artisan migrate --seed
    ```

5. **Storage Setup:**

    ```bash
    php artisan storage:link
    ```

6. **Frontend Assets:**

    ```bash
    npm install
    npm run dev
    ```

### 3. Microservices Setup

#### FastAPI ML Service

```bash
cd fastapi_app
pip install -r requirements.txt
uvicorn main:app --reload --port 8001
```

#### Email API (Spring Boot)

```bash
cd email-api
mvn spring-boot:run
```

#### Validator API (Spring Boot)

```bash
cd validator-api
mvn spring-boot:run
```

## How to Run the Application

### Local Development

Start all services in separate terminals:

1. **Laravel Development Server:**

    ```bash
    php artisan serve
    ```

2. **Laravel Queue Worker:**

    ```bash
    php artisan queue:work
    ```

3. **FastAPI ML Service:**

    ```bash
    cd fastapi_app
    uvicorn main:app --reload --port 8001
    ```

4. **Email API:**

    ```bash
    cd email-api
    mvn spring-boot:run
    ```

5. **Validator API:**

    ```bash
    cd validator-api
    mvn spring-boot:run
    ```

6. **Frontend Assets:**

   ```bash
   npm run dev
   ```

Access locally at `http://127.0.0.1:8000`

### Production Deployment (Render)

The system is deployed using Docker containers on Render:

1. **Main Laravel App**: Deployed with Dockerfile
2. **FastAPI Service**: Deployed from `fastapi_app/` directory
3. **Email API**: Deployed from `email-api/` directory  
4. **Validator API**: Deployed from `validator-api/` directory
5. **Database**: Supabase PostgreSQL (managed service)

**Live System**: [https://laravel-service.onrender.com](https://laravel-service.onrender.com)

## Default Admin Access

The system creates a default admin user during seeding:

- **Email**: `admin@autochain.com`
- **Password**: `password`

Access the admin dashboard at `/admin/dashboard`

## Project Structure

```text
Inventory-Mgt-System-25/
├── app/                   # Laravel application core
│   ├── Http/Controllers/  # Role-based controllers
│   ├── Models/            # Database models (User, Vendor, etc.)
│   ├── Mail/              # Email notification classes
│   └── Services/          # Business logic services
├── resources/
│   ├── views/dashboards/  # Role-specific dashboard views
│   ├── css/               # Role-specific stylesheets
│   └── js/                # Frontend JavaScript
├── database/
│   ├── migrations/        # Database schema migrations
│   └── seeders/           # Sample data seeders
├── fastapi_app/           # Python ML/Analytics API
│   ├── main.py            # FastAPI application
│   ├── requirements.txt   # Python dependencies
│   └── Dockerfile         # Docker configuration
├── email-api/             # Java Spring Boot email service
│   ├── src/main/java/     # Java source code
│   ├── pom.xml            # Maven dependencies
│   └── Dockerfile         # Docker configuration
├── validator-api/         # Java Spring Boot validation service
│   ├── src/main/java/     # Java source code
│   ├── pom.xml            # Maven dependencies
│   └── Dockerfile         # Docker configuration
├── ml/                    # Machine Learning scripts
│   ├── scripts/           # Forecasting algorithms
│   └── data/              # Training data
├── public/
│   ├── css/               # Compiled stylesheets
│   ├── js/                # Compiled JavaScript
│   └── images/            # Static images and assets
├── routes/
│   ├── web.php            # Web routes
│   ├── api.php            # API routes
│   └── auth.php           # Authentication routes
├── config/
│   ├── database.php       # Database configuration
│   ├── services.php       # External service configs
│   └── app.php            # Application settings
├── storage/
│   ├── app/uploads/       # User uploaded files
│   └── logs/              # Application logs
├── tests/
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
├── .env.example           # Environment template
├── Dockerfile             # Main Laravel app Docker config
├── docker-compose.yml     # Local development orchestration
├── render.yaml            # Render deployment configuration
├── composer.json          # PHP dependencies
├── package.json           # Node.js dependencies
└── README.md              # This file
```

## Available Commands

### Laravel Artisan Commands

```bash
# User management
php artisan approve-user {email}
php artisan create-admin {email} {password}
php artisan list-pending-users

# Scheduled tasks
php artisan send-scheduled-reports

# Database operations
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Queue management
php artisan queue:work
php artisan queue:restart

# Cache management
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Storage operations
php artisan storage:link
```

### Development Commands

```bash
# Frontend asset compilation
npm run dev          # Development build with hot reload
npm run build        # Production build
npm run watch        # Watch for changes

# Testing
php artisan test     # Run all tests
php artisan test --filter=UserTest  # Run specific test

# Code quality
./vendor/bin/phpstan analyse    # Static analysis
./vendor/bin/php-cs-fixer fix   # Code formatting
```

### Docker Commands

```bash
# Local development with Docker
docker-compose up -d              # Start all services
docker-compose down               # Stop all services
docker-compose logs laravel       # View Laravel logs
docker-compose exec laravel bash  # Access Laravel container

# Production deployment preparation
docker build -t inventory-system .
docker run -p 8000:80 inventory-system
```

## UI/UX Features

- **Responsive Design**: Mobile-friendly interfaces
- **Role-Specific Styling**: Custom CSS for each user role
- **Modern UI**: Clean, professional design with Tailwind CSS
- **Interactive Components**: Dynamic charts and real-time updates
- **Accessibility**: WCAG compliant design patterns

## Security Features

- **CSRF Protection**: Built-in Laravel CSRF tokens
- **Role-Based Authorization**: Middleware-based access control
- **Secure File Uploads**: Validated document uploads
- **Password Hashing**: Bcrypt password encryption
- **Session Management**: Secure session handling

## API Endpoints & Services

### Production Service URLs

- **Main Application**: https://larvelsercive.onrender.com
- **FastAPI ML Service**: https://fastapiserviuce.onrender.com
- **Email API**: https://springbootservice.onrender.com
- **Validator API**: https://validator-api-f40v.onrender.com

### Demo Credentials

- **Admin Email**: `admin@autochain.com`
- **Password**: `password`
- **Email API Username**: `admin` (Environment: `JAVA_MAIL_USERNAME`)

### Key API Endpoints

#### FastAPI ML Service Endpoints

- `POST /forecast` - Demand forecasting for car models
- `POST /segment-vendors` - Vendor segmentation analysis
- `POST /segment-retailers` - Retailer segmentation analysis
- `GET /health` - Service health check

#### Email API

- `POST /send-email` - Send notification emails
- `GET /health` - Service health check

#### Validator API

- `POST /validate-documents` - Document validation
- `POST /process-pdf` - PDF document processing
- `GET /health` - Service health check

## Database Schema

The system uses PostgreSQL (Supabase) with comprehensive tables for:

- Users and role management (6 user types)
- Inventory tracking and stock management
- Order management and fulfillment
- Facility visits and vendor validation
- Real-time communications and chat
- Scheduled reports and analytics
- System settings and configuration
- Document storage and validation scores

## How to Contribute

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is proprietary software. All rights reserved.

## Support

For technical support or questions, please contact the development team or create an issue in the repository.

## Mobile & Browser Support

- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Browser Support**: Chrome, Firefox, Safari, Edge
- **PWA Ready**: Progressive Web App capabilities
- **Real-time Updates**: WebSocket support for live notifications

## Performance & Monitoring

- **Cloud Hosting**: Render platform with auto-scaling
- **Database**: Managed PostgreSQL with connection pooling
- **CDN**: Static asset optimization
- **Caching**: Redis-compatible caching layer
- **Monitoring**: Built-in health checks for all services

---

**🌐 Live System**: [https://larvelsercive.onrender.com](https://larvelsercive.onrender.com)

### Built with ❤️ using Laravel 11, FastAPI, Spring Boot, and cloud-native technologies

#### Deployed on Render • Database by SQLite • Domain by Render