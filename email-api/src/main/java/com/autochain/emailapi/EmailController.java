package com.autochain.emailapi;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/v1")
public class EmailController {

    @Autowired
    private EmailService emailService;

    @PostMapping("/send-email")
    public ResponseEntity<String> sendEmail(@RequestBody EmailRequest emailRequest) {
        try {
            emailService.sendEmail(emailRequest.getTo(), emailRequest.getSubject(), emailRequest.getBody());
            return ResponseEntity.ok("Email sent successfully!");
        } catch (Exception e) {
            return ResponseEntity.status(500).body("Error sending email: " + e.getMessage());
        }
    }

    @GetMapping("/email-templates")
    public ResponseEntity<Map<String, Object>> getEmailTemplates() {
        Map<String, Object> templates = new HashMap<>();
        
        // User Approval Template
        Map<String, String> approvalTemplate = new HashMap<>();
        approvalTemplate.put("subject", "Your Application has been Approved!");
        approvalTemplate.put("body", "Dear {user_name},\n\nCongratulations! Your account for the Autochain Nexus platform has been approved.\n\nYou can now log in and access all the features available to you.\n\nLogin here: {login_url}\n\nBest Regards,\nThe Autochain Nexus Team");
        templates.put("user_approval", approvalTemplate);
        
        // User Rejection Template
        Map<String, String> rejectionTemplate = new HashMap<>();
        rejectionTemplate.put("subject", "Update on Your Application Status");
        rejectionTemplate.put("body", "Dear {user_name},\n\nThank you for your interest in the Autochain Nexus platform. After careful review, we regret to inform you that your application has been rejected at this time.\n\nIf you believe this was in error or have further questions, please contact our support team.\n\nBest Regards,\nThe Autochain Nexus Team");
        templates.put("user_rejection", rejectionTemplate);
        
        // Password Reset Template
        Map<String, String> passwordResetTemplate = new HashMap<>();
        passwordResetTemplate.put("subject", "Reset Your Password");
        passwordResetTemplate.put("body", "Dear user,\n\nPlease click the following link to reset your password: {reset_url}\n\nThis link will expire in 60 minutes. If you did not request a password reset, no further action is required.\n\nBest Regards,\nThe Autochain Nexus Team");
        templates.put("password_reset", passwordResetTemplate);
        
        return ResponseEntity.ok(templates);
    }
} 