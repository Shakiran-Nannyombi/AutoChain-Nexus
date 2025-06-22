# Autochain Nexus Inventory Management System

A modern, multi-role inventory management system for the automotive supply chain, built with Laravel 12, Vite, Tailwind CSS, and Java Spring Boot. The system supports multiple user roles (Manufacturer, Supplier, Vendor, Retailer, Analyst, Admin), custom registration with document uploads, vendor validation with Java backend integration, and a visually appealing, responsive UI.

---

## Project Structure

```
Inventory-Mgt-System-25/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── UserController.php           # Manages users, validation, approval/rejection
│   │   │   │   ├── VisitController.php          # Manages facility visits (scheduling, approval)
│   │   │   │   └── ValidationCriteriaController.php # Manages validation rules
│   │   │   └── Auth/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Mail/                          # Mailable classes for notifications
│   ├── Models/
│   │   ├── User.php                     # Main user model with roles
│   │   ├── FacilityVisit.php            # Model for facility visits
│   │   ├── UserDocument.php             # Model for user-uploaded documents
│   │   └── ValidationRule.php           # Model for validation rules
│   ├── Notifications/                 # Notification classes (e.g., password reset)
│   └── ...
│
├── database/
│   ├── migrations/                  # Migrations for users, roles, visits, validation, etc.
│   └── seeders/                     # Seeders for admin, test users, visits, validation rules
│
├── email-api/                       # Java Spring Boot email microservice
│   ├── src/main/java/com/autochain/emailapi/
│   │   ├── EmailController.java       # REST endpoint for sending emails
│   │   └── EmailService.java          # Service for handling email logic
│   └── pom.xml
│
├── resources/
│   ├── css/                         # CSS files (app.css, admin.css)
│   └── views/
│       ├── auth/
│       ├── dashboards/
│       │   └── admin/
│       │       ├── user-management.blade.php
│       │       ├── user-validation.blade.php
│       │       ├── visit-scheduling.blade.php
│       │       └── validation-criteria.blade.php
│       └── emails/                    # Email templates
│
├── routes/
│   └── web.php                      # Web routes for admin, auth, and user actions
│
├── validator-api/                   # Java Spring Boot validation microservice
│   ├── src/main/java/com/vendorvalidation/validator_api/
│   │   ├── ValidationController.java    # REST API for running validation
│   │   ├── VendorValidator.java         # Core validation logic (scoring)
│   │   └── PDFProcessor.java            # Extracts text and data from PDFs
│   └── pom.xml
│
└── ...
```

---

## Features

- **Multi-role Authentication**: Support for Manufacturer, Supplier, Vendor, Retailer, Analyst, and Admin roles
- **Document Upload**: File uploads for registration (documents, profile pictures)
- **Vendor Validation System**: Java Spring Boot backend for advanced vendor validation
- **File Format Validation**: Support for PDF, DOC, DOCX, JPG, JPEG, PNG files
- **Admin Dashboard**: Comprehensive admin interface with user management and validation criteria
- **Responsive UI**: Modern, responsive design with Tailwind CSS
- **Real-time Validation**: Integration between Laravel frontend and Java backend
- **Database Management**: Complete database structure with migrations and seeders

### Decoupled Email Service
The system now uses a dedicated Java-based microservice for handling all email notifications. This decouples the email functionality from the main Laravel application, improving modularity and allowing email services to be scaled independently. The email API runs on port `8082` and provides a simple endpoint for sending emails.

### Vendor Validation & Automatic Visit Scheduling
The vendor validation process has been significantly enhanced:
- **PDF Content Extraction**: The Java validator API now processes the content of uploaded PDF documents to extract key data points.
- **Comprehensive Scoring**: It calculates a detailed validation score based on:
    - Financial Stability (Years in business, revenue)
    - Reputation (Awards, reviews)
    - Regulatory Compliance (ISO certifications, licenses)
    - Profile Completeness
- **Automatic Visit Scheduling**: If a **vendor's** validation score exceeds a predefined threshold (e.g., 70), a facility visit is automatically scheduled two weeks in the future.
- **Email Notifications**: The vendor is automatically notified via email once the visit has been scheduled.

### Visit Scheduling & Management
A dedicated "Visit Scheduling" page is available in the admin dashboard with the following features:
- **Vendor-Specific View**: The page is filtered to display facility visits for **vendors only**.
- **Modern UI**: A redesigned interface with stat cards for a clear overview of pending, approved, rejected, and auto-scheduled visits.
- **Full Visit Management**: Admins can approve, reject, or reschedule pending visits.
- **Calendar View**: An integrated calendar modal provides a monthly and daily overview of all scheduled visits.
- **Email Confirmations**: Admins can send email confirmations for approved or rescheduled visits directly from the interface.

---

## Document Storage System

The system uses a dedicated `user_documents` table to store uploaded files with the following structure:

### Database Schema
- **user_documents** table:
  - `id` - Primary key
  - `user_id` - Foreign key to users table
  - `document_type` - Type of document (`profile_picture`, `supporting_document`)
  - `file_path` - Path to the stored file
  - `created_at`, `updated_at` - Timestamps

### File Storage
- **Profile Pictures**: Stored in `storage/app/public/profile_pictures/`
- **Supporting Documents**: Stored in `storage/app/public/supporting_documents/`
- **Public Access**: Files are accessible via `/storage/` URL path

### User Model Relationships
```php
// Get all user documents
$user->documents

// Get profile picture only
$user->profilePicture()

// Get supporting documents only
$user->supportingDocuments()
```

### Registration Process
1. User uploads profile picture and supporting documents during registration
2. Files are stored in the appropriate directories
3. Document records are created in `user_documents` table
4. Each document is linked to the user via `user_id` foreign key

### 2. Email API Setup

1. **Navigate to the Java project directory**
   ```bash
   cd email-api
   ```

2. **Set JAVA_HOME environment variable**
   ```bash
   # For Linux/Mac
   export JAVA_HOME=/path/to/your/jdk
   
   # For Windows
   set JAVA_HOME=C:\path\to\your\jdk
   ```

3. **Run the Spring Boot application**
   ```bash
   ./mvnw spring-boot:run
   ```
   - The Java API will be available at [http://localhost:8082](http://localhost:8082)

### 3. Java Backend Setup

1. **Navigate to the Java project directory**
   ```bash
   cd validator-api
   ```

2. **Set JAVA_HOME environment variable**
   ```bash
   # For Linux/Mac
   export JAVA_HOME=/path/to/your/jdk
   
   # For Windows
   set JAVA_HOME=C:\path\to\your\jdk
   ```

3. **Compile the project**
   ```bash
   ./mvnw compile
   ```

4. **Run the Spring Boot application**
   ```bash
   ./mvnw spring-boot:run
   ```
   - The Java API will be available at [http://localhost:8080](http://localhost:8080)

### 4. Integration Testing

1. **Access the admin dashboard**
   - Go to [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
   - Login with admin credentials (see Default Users section)

2. **Navigate to Validation Criteria**
   - Go to Admin Dashboard → Validation Criteria
   - You'll see pre-configured validation rules including file format validation

3. **Sync with Java Backend**
   - Click "Force Sync with Backend" to send validation rules to the Java API
   - Check the Java console for sync confirmation messages

4. **Test File Validation**
   - The system now supports validation of PDF, DOC, DOCX, JPG, JPEG, and PNG files
   - Upload files with different extensions to test the validation system

---

## Default Users

- **Admin**
  - Email: `admin@autochain.com`
  - Password: `admin123`
- **Test User**
  - Email: `test@example.com`
  - Password: (set by factory, check seeder)

---

## API Endpoints

### Java Backend (Port 8080)

- **POST** `/api/v1/sync-rules` - Sync validation rules from Laravel
- **POST** `/api/v1/validate` - Validate vendor documents with file path parameter

### Laravel Frontend (Port 8000)

- **GET** `/admin/validation-criteria` - Validation criteria management page
- **POST** `/admin/validation/store` - Add new validation rule
- **POST** `/admin/validation/sync` - Force sync with Java backend

---

## Validation Rules

The system includes several pre-configured validation rules:

1. **Allowed File Formats**: `pdf,doc,docx,jpg,jpeg,png`
2. **Financial Score Threshold**: `70`
3. **Reputation Score Threshold**: `60`
4. **ISO Certification Required**: `ISO`

---

## Troubleshooting

### Java Backend Issues
- **JAVA_HOME not set**: Ensure JAVA_HOME points to a valid JDK installation
- **Port 8080 in use**: Change the port in `application.properties` or stop conflicting services
- **Compilation errors**: Check that all Java files have correct package declarations

### Laravel Issues
- **Database connection**: Verify MySQL is running and credentials are correct
- **Asset compilation**: Ensure Node.js and npm are properly installed
- **Validation sync**: Check that the Java backend is running on port 8080

---

## Development Notes

- **File Structure**: Admin-specific styles are in `resources/css/admin.css`
- **Validation Logic**: Core validation is handled by the Java backend
- **Real-time Sync**: Validation rules are automatically synced between Laravel and Java
- **Extensible**: New validation rules can be added through the admin interface
- **Logging**: Check Java console for detailed validation process logs

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test both Laravel and Java components
5. Submit a pull request

---

## License

This project is licensed under the MIT License.
