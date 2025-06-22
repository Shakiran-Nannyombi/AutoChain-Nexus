# Inventory Management & Vendor Validation System

This is a comprehensive web application designed to streamline inventory management and automate the vendor validation process. It serves as a central platform for various stakeholders in the supply chain, including manufacturers, suppliers, vendors, and retailers, all managed by a powerful admin dashboard.

The system is built with a microservices-oriented architecture, featuring a core Laravel 12 application and two supporting Java-based REST APIs for specialized tasks.

## Key Features

*   **Multi-Role Authentication**: A robust authentication system catering to different user roles (Admin, Manufacturer, Supplier, Vendor, Retailer, Analyst).
*   **Automated User Validation**: A multi-step user registration process where new users submit documents and await admin approval. The system can automatically score and validate users against predefined criteria.
*   **Powerful Admin Dashboard**: A central hub for administrators to:
    *   Manage all users and their roles.
    *   Approve or reject new user applications.
    *   View system-wide analytics and user registration trends.
    *   Schedule and automate the dispatch of customized email reports.
    *   Configure system settings and validation criteria.
    *   Perform and manage database backups.
    *   Search globally for users and pages within the dashboard.
*   **Role-Specific Dashboards**: Tailored dashboard views for each user role, providing relevant information and actions.
*   **Facility Visit Scheduling**: Functionality for admins to schedule and track on-site facility visits for vendor validation.
*   **System Health & Analytics**: Visual charts and metrics for monitoring user activity, validation funnels, and overall system health.
*   **Microservice Integration**:
    *   **Email Service**: A dedicated Java API to handle sending emails, such as user approval/rejection notices and scheduled reports.
    *   **Validation Service**: A Java API that processes uploaded PDF documents to extract data and aid in the automated validation process.

## System Architecture

The application follows a modern architecture pattern:

*   **Backend**: A **Laravel 12** application serves as the core backend, handling business logic, database management, and routing.
*   **Frontend**: The user interface is built with **Blade** templates, styled with **Tailwind CSS** and **Vite** for asset bundling.
*   **Database**: **MySQL** is used for the primary data store.
*   **Microservices**:
    *   **Email API**: A **Spring Boot** application that exposes endpoints for sending emails.
    *   **Validator API**: A **Spring Boot** application that uses Apache PDFBox and Tika to parse and validate documents.
*   **Task Scheduling**: Laravel's built-in scheduler (cron) is used for background tasks like sending scheduled reports.
*   **Job Queues**: Laravel Queues are used to handle time-consuming tasks like sending emails without blocking the user interface.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

*   **PHP 8.2** or higher
*   **Composer**
*   **Node.js** and **npm**
*   **Java 17** or higher
*   **Maven**
*   **A MySQL database server**

## Installation & Setup

Follow these steps to get the project up and running on your local machine.

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd Inventory-Mgt-System-25
```

### 2. Setup Laravel Backend

1.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

2.  **Create Environment File:**
    Copy the example environment file. The `post-root-package-install` script might have already done this. If not, run:
    ```bash
    cp .env.example .env
    ```

3.  **Configure `.env` File:**
    Open the `.env` file and update the database credentials (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) to match your local MySQL setup.

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run Database Migrations and Seeders:**
    This will create all the necessary tables and populate them with initial data, including the admin user and other sample users.
    ```bash
    php artisan migrate --seed
    ```

6.  **Link Storage:**
    ```bash
    php artisan storage:link
    ```

7.  **Install NPM Dependencies and Build Assets:**
    ```bash
    npm install
    npm run dev
    ```
    Keep the `npm run dev` process running in a separate terminal to recompile assets as you make changes.

### 3. Setup Java Microservices

You will need to run both the `email-api` and `validator-api`. For each service, navigate to its directory and run it.

1.  **Navigate to the API directory:**
    ```bash
    # For the validator API
    cd validator-api
    ```
    or
    ```bash
    # For the email API
    cd email-api
    ```

2.  **Build and Run with Maven:**
    Use the Spring Boot Maven plugin to run the application.
    ```bash
    mvn spring-boot:run
    ```
    Open two separate terminals to run both services simultaneously.

## Running the Application

Once everything is set up, you need to have four processes running in separate terminal windows:

1.  **Laravel Development Server:**
    ```bash
    php artisan serve
    ```

2.  **Laravel Queue Worker** (to process jobs like sending emails):
    ```bash
    php artisan queue:work
    ```

3.  **Email API** (run from the `email-api` directory):
    ```bash
    mvn spring-boot:run
    ```

4.  **Validator API** (run from the `validator-api` directory):
    ```bash
    mvn spring-boot:run
    ```

You can now access the application at `http://127.0.0.1:8000`.

## Admin Access

The database seeder creates a default admin user with the following credentials:

-   **Email**: `admin@autochain.com`
-   **Password**: `password`

You can use these to log into the admin dashboard at `/admin/dashboard`.
