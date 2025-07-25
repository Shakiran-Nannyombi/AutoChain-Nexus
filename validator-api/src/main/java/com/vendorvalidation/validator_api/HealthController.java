package com.vendorvalidation.validator_api;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class HealthController {
    
    @GetMapping("/")
    public String home() {
        return "Validator API Service is running";
    }
    
    @GetMapping("/health")
    public String health() {
        return "OK";
    }
}