# Email API Service

A Spring Boot microservice for handling email notifications in the Autochain Nexus platform.

## Features

- **Mock Email Service**: Works without external SMTP configuration
- **SMTP Integration**: Optional real email sending when configured
- **RESTful API**: Simple HTTP endpoints for email operations
- **Error Handling**: Graceful fallback to mock service if SMTP fails

## API Endpoints

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

## Configuration

### Mock Mode (Default)
The service runs in mock mode by default, logging emails to the console instead of sending them. No external configuration required.

### SMTP Mode (Optional)
To enable real email sending, uncomment and configure the following in `application.properties`:

```properties
spring.mail.host=your-smtp-host
spring.mail.port=587
spring.mail.username=your-email@example.com
spring.mail.password=your-email-password
spring.mail.properties.mail.smtp.auth=true
spring.mail.properties.mail.smtp.starttls.enable=true
```

## Running the Service

```bash
cd email-api
export JAVA_HOME=/opt/jvm/jdk-17.0.2
./mvnw spring-boot:run
```

The service will start on port 8082.

## Integration with Laravel

The Laravel application is configured to send emails to this service at:
- User approval notifications
- User rejection notifications  
- Password reset notifications

## Logs

In mock mode, emails are logged to the console with the format:
```
=== MOCK EMAIL SENT ===
To: recipient@example.com
Subject: Email Subject
Body: Email body content
=== END MOCK EMAIL ===
``` 