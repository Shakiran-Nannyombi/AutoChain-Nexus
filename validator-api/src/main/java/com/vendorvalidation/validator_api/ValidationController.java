package com.vendorvalidation.validator_api;

import org.springframework.web.bind.annotation.*;
import java.util.List;
import java.io.File;

@RestController
@RequestMapping("/api/v1")
public class ValidationController {

    private final VendorValidator vendorValidator = new VendorValidator();

    // This will hold the rules synced from Laravel
    private List<ValidationRule> validationRules;

    @PostMapping("/sync-rules")
    public String syncRules(@RequestBody List<ValidationRule> rules) {
        this.validationRules = rules;
        System.out.println("Successfully synced " + rules.size() + " rules.");
        return "Rules synced successfully";
    }

    @PostMapping("/validate")
    public String validateVendor(@RequestBody VendorData vendorData) {
        if (this.validationRules == null || this.validationRules.isEmpty()) {
            return "{\"score\": 0, \"message\": \"Validation failed: No rules have been synced.\"}";
        }

        try {
            // The validator will now directly use the data object
            return vendorValidator.validate(vendorData, this.validationRules);
        } catch (Exception e) {
            return "{\"score\": 0, \"message\": \"Validation failed: " + e.getMessage() + "\"}";
        }
    }
} 