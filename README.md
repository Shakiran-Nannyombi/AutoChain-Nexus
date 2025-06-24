# Autochain Nexus
## Documentation

<div align="center">
  <img src="public/images/logo.png" alt="Autochain Nexus Logo" width="200" style="background-color: white; padding: 20px; border-radius: 10px;">
</div>

A comprehensive multi-role inventory management system built with Laravel 12, featuring role-based dashboards, automated vendor validation, and microservices architecture. This system serves manufacturers, suppliers, vendors, retailers, analysts, and administrators with tailored interfaces and workflows.

## 🚀 Key Features

### 🔐 Multi-Role Authentication System
- **6 User Roles**: Admin, Manufacturer, Supplier, Vendor, Retailer, Analyst
- **Secure Registration**: Multi-step approval process with document upload
- **Role-Based Access Control**: Each role has specific permissions and dashboard access
- **Profile Management**: Users can update their information and profile photos
- **Password Reset**: Secure email-based password reset functionality

### 👨‍💼 Admin Dashboard
- **User Management**: Approve/reject new user applications
- **System Analytics**: Real-time charts and metrics
- **Global Search**: Search across users and dashboard pages
- **Scheduled Reports**: Automated email report generation
- **Database Backups**: Automated backup management
- **System Settings**: Configure validation rules and criteria
- **Facility Visits**: Schedule and track on-site validations
- **Communications**: Internal messaging system
- **User Activity Monitoring**: Track user logins and activity

### 🏭 Manufacturer Dashboard
- **Inventory Overview**: Real-time stock levels and alerts
- **Production Planning**: Demand prediction and capacity planning
- **Quality Control**: Checklists and quality assurance tools
- **Supplier Management**: Track supplier performance and relationships
- **Analytics**: Production metrics and trend analysis
- **Chat System**: Internal communication platform
- **Document Management**: Upload and manage production documents

### 🛒 Retailer Dashboard
- **Stock Overview**: Current inventory levels and alerts
- **Sales Updates**: Real-time sales data and trends
- **Order Management**: Place and track orders with suppliers
- **Notifications**: System alerts and updates
- **Chat System**: Communicate with suppliers and vendors
- **Analytics**: Sales performance and inventory turnover

### 📊 Analyst Dashboard
- **Data Analytics**: Comprehensive business intelligence
- **Inventory Analysis**: Stock optimization recommendations
- **Market Trends**: Industry analysis and forecasting
- **Performance Metrics**: KPI tracking and reporting
- **Predictive Analytics**: Demand forecasting models
- **Custom Reports**: Generate tailored business reports

### 🚚 Supplier Dashboard
- **Order Management**: Track incoming orders and fulfillment
- **Inventory Tracking**: Real-time stock levels
- **Delivery History**: Complete delivery tracking
- **Checklist Receipt**: Quality control checklists
- **Chat System**: Customer communication
- **Performance Metrics**: Supplier performance analytics

### 🏪 Vendor Dashboard
- **Delivery Management**: Track deliveries and schedules
- **Inventory Control**: Stock management and alerts
- **Order Processing**: Handle incoming orders
- **Analytics**: Sales and performance metrics
- **Chat System**: Customer and partner communication
- **Document Management**: Upload delivery documents

## 🛡️ Vendor Onboarding & Approval Flow

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

## 🏗️ System Architecture

### Core Technologies
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL
- **Asset Bundling**: Vite
- **Authentication**: Laravel Breeze with custom role system

### Microservices
- **Email API**: Spring Boot application for email handling
- **Validator API**: Spring Boot application for document processing
- **PDF Processing**: Apache PDFBox and Tika integration

### Key Components
- **Task Scheduling**: Laravel Scheduler for automated tasks
- **Job Queues**: Background processing for emails and reports
- **File Storage**: Laravel Storage with symbolic links
- **Middleware**: Custom authentication and role-based access control

## 📋 Prerequisites

Before installation, ensure you have:
- **PHP 8.2** or higher
- **Composer**
- **Node.js** and **npm**
- **Java 17** or higher
- **Maven**
- **MySQL** database server
- **Git**

## 🛠️ Installation & Setup

### 1. Clone the Repository
```bash
git clone <your-repository-url>
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
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
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

### 3. Java Microservices Setup

#### Email API
    ```bash
    cd email-api
mvn spring-boot:run
    ```

#### Validator API
    ```bash
cd validator-api
    mvn spring-boot:run
    ```

## 🚀 Running the Application

Start all services in separate terminals:

1. **Laravel Development Server:**
    ```bash
    php artisan serve
    ```

2. **Laravel Queue Worker:**
    ```bash
    php artisan queue:work
    ```

3. **Email API** (from `email-api` directory):
    ```bash
    mvn spring-boot:run
    ```

4. **Validator API** (from `validator-api` directory):
    ```bash
    mvn spring-boot:run
    ```

5. **Frontend Assets** (for development):
   ```bash
   npm run dev
   ```

Access the application at `http://127.0.0.1:8000`

## 👤 Default Admin Access

The system creates a default admin user during seeding:

- **Email**: `admin@autochain.com`
- **Password**: `password`

Access the admin dashboard at `/admin/dashboard`

## 📁 Project Structure

```
Inventory-Mgt-System-25/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin-specific controllers
│   │   ├── Auth/           # Authentication controllers
│   │   └── ...             # Role-specific controllers
│   ├── Models/             # Eloquent models
│   ├── Mail/               # Email templates
│   └── Services/           # Business logic services
├── resources/
│   ├── views/
│   │   ├── dashboards/     # Role-specific dashboard views
│   │   ├── auth/           # Authentication views
│   │   └── components/     # Reusable Blade components
│   └── css/                # Role-specific stylesheets
├── routes/
│   ├── web.php            # Main application routes
│   └── auth.php           # Authentication routes
├── email-api/             # Spring Boot email service
└── validator-api/         # Spring Boot validation service
```

## 🔧 Available Commands

### Laravel Artisan Commands
```bash
# User management
php artisan approve-user {email}
php artisan create-admin {email} {password}
php artisan list-pending-users

# Scheduled tasks
php artisan send-scheduled-reports
```

## 🎨 UI/UX Features

- **Responsive Design**: Mobile-friendly interfaces
- **Role-Specific Styling**: Custom CSS for each user role
- **Modern UI**: Clean, professional design with Tailwind CSS
- **Interactive Components**: Dynamic charts and real-time updates
- **Accessibility**: WCAG compliant design patterns

## 🔒 Security Features

- **CSRF Protection**: Built-in Laravel CSRF tokens
- **Role-Based Authorization**: Middleware-based access control
- **Secure File Uploads**: Validated document uploads
- **Password Hashing**: Bcrypt password encryption
- **Session Management**: Secure session handling

## 📊 Database Schema

The system includes comprehensive database tables for:
- Users and role management
- Inventory tracking
- Order management
- Facility visits
- Communications
- Scheduled reports
- System settings
- User documents and validation

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📝 License

This project is proprietary software. All rights reserved.

## 🆘 Support

For technical support or questions, please contact the development team or create an issue in the repository.

---

**Built with ❤️ using Laravel 12, Spring Boot, and modern web technologies**