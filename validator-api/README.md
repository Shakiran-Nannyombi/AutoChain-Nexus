# Validator API Service

A Spring Boot microservice for automated vendor validation in the Autochain Nexus platform.

---

## 📦 Overview

The Validator API is a standalone microservice responsible for validating vendor applications. It analyzes uploaded documents, financial data, reputation, and compliance, returning a comprehensive validation score and log to the main Laravel application.

---

## ✨ Features

- **RESTful API**: HTTP endpoints for rule syncing and vendor validation
- **Dynamic Validation Rules**: Rules are synced from Laravel and applied in real time
- **Document Analysis**: Extracts and analyzes data from uploaded files (PDF, DOC, images, etc.)
- **Comprehensive Scoring**: Evaluates financial stability, reputation, compliance, and profile completeness
- **Detailed Logs**: Returns a full breakdown of the validation process
- **Easy Integration**: Designed for seamless use with Laravel or other platforms

---

## ⚙️ How It Works

- The Laravel backend syncs validation rules to the Validator API.
- When a vendor registers, Laravel sends vendor data and document paths to the Validator API.
- The API processes the documents, applies the rules, and returns a score and detailed log.
- The score is used to determine if the vendor passes validation or requires further review.

---

## 🛣️ API Endpoints

### Sync Validation Rules
```
POST /api/v1/sync-rules
Content-Type: application/json

[
  { "name": "Allowed File Formats", "value": "pdf,doc,docx,jpg,jpeg,png" },
  ...
]
```
- Syncs the latest validation rules from Laravel to the Validator API.

### Validate Vendor
```
POST /api/v1/validate
Content-Type: application/json

{
  "documentPaths": ["/path/to/file1.pdf", ...],
  ...other vendor data fields...
}
```
- Validates a vendor using the synced rules and uploaded documents.
- **Response:** JSON with `score`, `message`, `log`, and breakdown of scores.

---

## 🧠 Validation Logic

- **File Format Check**: Ensures all uploaded documents match allowed formats.
- **Document Analysis**: Extracts data (financial, reputation, compliance) from files using PDF/image processing.
- **Financial Stability**: Scores based on years in business, revenue, employee count.
- **Reputation**: Scores for awards, reviews, industry memberships.
- **Regulatory Compliance**: Checks for required licenses, certifications, and legal standing.
- **Profile Completeness**: Scores for providing all required profile fields.
- **Comprehensive Log**: Returns a detailed log of each validation step and score.

---

## 🛠️ Configuration

- No special configuration required for development (mock file paths are accepted).
- For production, ensure the API can access the file storage used by Laravel.
- Edit `src/main/resources/application.properties` to change server port or other settings.

---

## 🚀 Setup & Running

1. **Install Java 17+ and Maven**
2. **Clone the repository and navigate to the validator-api folder:**
   ```bash
   cd validator-api
   ```
3. **Run the service:**
   ```bash
   ./mvnw spring-boot:run
   ```
   (Or use `mvn spring-boot:run` if Maven is installed globally)

- The service will start on port `8083` by default (or as configured).

---

## 🔗 Integration with Laravel

- The main Laravel app is configured to:
  - Sync validation rules to the Validator API
  - Send vendor data and document paths for validation
  - Use the returned score and log to determine vendor approval status
- You can customize the rules and validation logic as needed.

---

## 🛠️ Troubleshooting

- **Validation always fails?**
  - Ensure rules are synced before validating vendors.
  - Check that document paths are accessible and in allowed formats.
- **API not reachable?**
  - Confirm the service is running and the port matches Laravel's configuration.
- **Integration issues?**
  - Review logs for errors and ensure the Laravel app points to the correct Validator API URL and port.

---

## 🤝 Contributing

- Fork the repository, make changes, and submit a pull request.
- For feature requests or bug reports, open an issue.

---

**Part of the Autochain Nexus platform. Built with Spring Boot.** 