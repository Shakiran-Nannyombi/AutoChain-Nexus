# Email API Service

A Spring Boot microservice for handling all email notifications in the Autochain Nexus platform.

---

## 📦 Overview

The Email API is a standalone microservice responsible for sending emails (such as user approvals, rejections, password resets, and scheduled reports) on behalf of the main Laravel application. It supports both mock (development) and real SMTP (production) modes.

---

## ✨ Features

- **RESTful API**: Simple HTTP endpoints for sending emails
- **Mock Email Service**: Works out-of-the-box for development (no SMTP required)
- **SMTP Integration**: Real email delivery when SMTP is configured
- **Error Handling**: Graceful fallback to mock mode if SMTP fails
- **Easy Integration**: Designed for seamless use with Laravel or other platforms

---

## ⚙️ How It Works

- The Laravel backend makes HTTP POST requests to the Email API to trigger email notifications.
- By default, the service logs emails to the console (mock mode).
- If SMTP is configured, emails are sent via your SMTP provider.
- If SMTP fails, the service logs the error and falls back to mock mode.

---

## 🛣️ API Endpoints

### Send Email
```
POST /api/v1/send-email
Content-Type: application/json

{
  "to": "recipient@example.com",
  "subject": "Email Subject",
  "body": "Email body content"
}
```
- **to**: Recipient email address (required)
- **subject**: Email subject (required)
- **body**: Email body (required)

**Response:**
- `200 OK` on success
- `4xx/5xx` on error (with error message)

---

## 🛠️ Configuration

### Mock Mode (Default)
- No setup required. Emails are logged to the console for development/testing.

### SMTP Mode (Production)
- To enable real email sending, edit `src/main/resources/application.properties`:

```properties
spring.mail.host=your-smtp-host
spring.mail.port=587
spring.mail.username=your-email@example.com
spring.mail.password=your-email-password
spring.mail.properties.mail.smtp.auth=true
spring.mail.properties.mail.smtp.starttls.enable=true
```
- Restart the service after updating configuration.

---

## 🚀 Setup & Running

1. **Install Java 17+ and Maven**
2. **Clone the repository and navigate to the email-api folder:**
   ```bash
   cd email-api
   ```
3. **Run the service:**
   ```bash
   ./mvnw spring-boot:run
   ```
   (Or use `mvn spring-boot:run` if Maven is installed globally)

- The service will start on port `8082` by default.

---

## 🔗 Integration with Laravel

- The main Laravel app is configured to send HTTP requests to this service for:
  - User approval/rejection notifications
  - Password reset emails
  - Scheduled report delivery
- You can customize the endpoints or add new email types as needed.

---

## 📝 Logs

- In **mock mode**, emails are logged to the console in this format:
  ```
  === MOCK EMAIL SENT ===
  To: recipient@example.com
  Subject: Email Subject
  Body: Email body content
  === END MOCK EMAIL ===
  ```
- In **SMTP mode**, delivery status and errors are logged.

---

## 🛠️ Troubleshooting

- **Emails not sending?**
  - Check SMTP configuration in `application.properties`.
  - Review logs for authentication or connection errors.
  - In mock mode, verify emails appear in the console.
- **Port conflict?**
  - Change the server port in `application.properties` with `server.port=8082` (or another port).
- **Integration issues?**
  - Ensure the Laravel app points to the correct Email API URL and port.

---

## 🤝 Contributing

- Fork the repository, make changes, and submit a pull request.
- For feature requests or bug reports, open an issue.

---

**Part of the Autochain Nexus platform. Built with Spring Boot.** 