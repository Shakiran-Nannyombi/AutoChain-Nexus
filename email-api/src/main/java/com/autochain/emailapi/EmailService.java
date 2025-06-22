package com.autochain.emailapi;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.stereotype.Service;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@Service
public class EmailService {

    private static final Logger logger = LoggerFactory.getLogger(EmailService.class);

    @Autowired(required = false)
    private JavaMailSender mailSender;

    public void sendEmail(String to, String subject, String body) {
        // Check if we have a configured mail sender
        if (mailSender != null) {
            try {
                SimpleMailMessage message = new SimpleMailMessage();
                message.setTo(to);
                message.setSubject(subject);
                message.setText(body);
                mailSender.send(message);
                logger.info("Email sent successfully via SMTP to: {}", to);
            } catch (Exception e) {
                logger.error("Failed to send email via SMTP, falling back to mock: {}", e.getMessage());
                sendMockEmail(to, subject, body);
            }
        } else {
            // No SMTP configured, use mock email service
            sendMockEmail(to, subject, body);
        }
    }

    private void sendMockEmail(String to, String subject, String body) {
        logger.info("=== MOCK EMAIL SENT ===");
        logger.info("To: {}", to);
        logger.info("Subject: {}", subject);
        logger.info("Body: {}", body);
        logger.info("=== END MOCK EMAIL ===");
        
        // In a real implementation, you could:
        // 1. Store emails in a database
        // 2. Send to a message queue
        // 3. Integrate with a different email service
        // 4. Send to a webhook
    }
} 