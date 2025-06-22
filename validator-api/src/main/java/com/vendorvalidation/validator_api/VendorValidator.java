package com.vendorvalidation.validator_api;

import java.util.List;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Map;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.databind.node.ObjectNode;

/**
 * VendorValidator class for validating vendor data sent from the main application.
 * This class analyzes vendor data and calculates a score based on financial stability,
 * reputation, and regulatory compliance.
 */
public class VendorValidator {
    
    private final PDFProcessor pdfProcessor = new PDFProcessor();
    
    /**
     * Helper method to find a specific rule's value from the list.
     * @param rules The list of validation rules
     * @param ruleName The name of the rule to find
     * @param defaultValue A default value to return if the rule is not found
     * @return The rule's value or the default value
     */
    private String findRuleValue(List<ValidationRule> rules, String ruleName, String defaultValue) {
        return rules.stream()
                .filter(rule -> rule.getName().equalsIgnoreCase(ruleName))
                .map(ValidationRule::getValue)
                .findFirst()
                .orElse(defaultValue);
    }

    /**
     * Validates a vendor based on the provided data and rules.
     * This method calculates a comprehensive score based on document analysis,
     * financial stability, reputation, and regulatory compliance.
     * @param vendorData The vendor data object from Laravel
     * @param rules A list of validation rules to apply
     * @return JSON string with validation results
     */
    public String validate(VendorData vendorData, List<ValidationRule> rules) {
        int finalScore = 0;
        StringBuilder validationLog = new StringBuilder();
        Map<String, Object> extractedData = new HashMap<>();

        // Step 1: Validate file formats and extract data from documents
        boolean allFilesValid = true;
        String allowedFormats = findRuleValue(rules, "Allowed File Formats", "pdf,doc,docx,jpg,jpeg,png");
        validationLog.append("Allowed formats: ").append(allowedFormats).append(". ");

        if (vendorData.getDocumentPaths() == null || vendorData.getDocumentPaths().isEmpty()) {
            allFilesValid = false;
            validationLog.append("No documents submitted. ");
        } else {
            for (String docPath : vendorData.getDocumentPaths()) {
                if (!validateFileFormat(docPath, allowedFormats)) {
                    allFilesValid = false;
                    validationLog.append("Invalid format for '").append(docPath).append("'. ");
                    break; 
                } else {
                    // Process document and extract data
                    Map<String, Object> docData = pdfProcessor.processDocument(docPath);
                    if (!docData.isEmpty()) {
                        extractedData.putAll(docData);
                        validationLog.append("Successfully processed '").append(docPath).append("'. ");
                    }
                }
            }
        }
        
        if (allFilesValid) {
            finalScore += 20; // Base score for valid documents
            validationLog.append("All document formats are valid. +20 points. ");
        }

        // Step 2: Financial Stability Analysis
        int financialScore = analyzeFinancialStability(extractedData, rules);
        finalScore += financialScore;
        validationLog.append("Financial analysis score: ").append(financialScore).append(" points. ");

        // Step 3: Reputation Analysis
        int reputationScore = analyzeReputation(extractedData, rules);
        finalScore += reputationScore;
        validationLog.append("Reputation analysis score: ").append(reputationScore).append(" points. ");

        // Step 4: Regulatory Compliance Analysis
        int complianceScore = analyzeCompliance(extractedData, rules);
        finalScore += complianceScore;
        validationLog.append("Compliance analysis score: ").append(complianceScore).append(" points. ");

        // Step 5: Profile Completeness
        int profileScore = analyzeProfileCompleteness(vendorData);
        finalScore += profileScore;
        validationLog.append("Profile completeness score: ").append(profileScore).append(" points. ");

        // Ensure score does not exceed 100
        finalScore = Math.min(finalScore, 100);

        // Create a comprehensive JSON response
        try {
            ObjectMapper mapper = new ObjectMapper();
            ObjectNode response = mapper.createObjectNode();
            response.put("score", finalScore);
            response.put("message", "Comprehensive validation completed.");
            response.put("log", validationLog.toString());
            response.put("financial_score", financialScore);
            response.put("reputation_score", reputationScore);
            response.put("compliance_score", complianceScore);
            response.put("profile_score", profileScore);
            response.put("extracted_data", mapper.valueToTree(extractedData));
            return mapper.writerWithDefaultPrettyPrinter().writeValueAsString(response);
        } catch (Exception e) {
            return "{\"score\": 0, \"message\": \"Error creating JSON response: " + e.getMessage() + "\"}";
        }
    }
    
    /**
     * Analyzes financial stability based on extracted data.
     * @param extractedData Data extracted from documents
     * @param rules Validation rules
     * @return Financial stability score
     */
    private int analyzeFinancialStability(Map<String, Object> extractedData, List<ValidationRule> rules) {
        int score = 0;
        
        @SuppressWarnings("unchecked")
        Map<String, String> financialData = (Map<String, String>) extractedData.get("financial_data");
        if (financialData == null) {
            return 0;
        }
        
        // Years in business analysis
        String yearsInBusiness = financialData.get("years_in_business");
        if (yearsInBusiness != null) {
            int years = Integer.parseInt(yearsInBusiness);
            if (years >= 10) score += 25;
            else if (years >= 5) score += 20;
            else if (years >= 2) score += 15;
            else if (years >= 1) score += 10;
        }
        
        // Revenue analysis
        String revenue = financialData.get("revenue");
        if (revenue != null) {
            try {
                double revenueValue = Double.parseDouble(revenue);
                if (revenueValue >= 10000000) score += 25; // $10M+
                else if (revenueValue >= 1000000) score += 20; // $1M+
                else if (revenueValue >= 100000) score += 15; // $100K+
                else if (revenueValue >= 10000) score += 10; // $10K+
            } catch (NumberFormatException e) {
                // Ignore parsing errors
            }
        }
        
        // Employee count analysis
        String employeeCount = financialData.get("employee_count");
        if (employeeCount != null) {
            try {
                int employees = Integer.parseInt(employeeCount);
                if (employees >= 100) score += 15;
                else if (employees >= 50) score += 12;
                else if (employees >= 10) score += 8;
                else if (employees >= 5) score += 5;
            } catch (NumberFormatException e) {
                // Ignore parsing errors
            }
        }
        
        return Math.min(score, 30); // Cap financial score at 30 points
    }
    
    /**
     * Analyzes reputation based on extracted data.
     * @param extractedData Data extracted from documents
     * @param rules Validation rules
     * @return Reputation score
     */
    private int analyzeReputation(Map<String, Object> extractedData, List<ValidationRule> rules) {
        int score = 0;
        
        @SuppressWarnings("unchecked")
        Map<String, String> reputationData = (Map<String, String>) extractedData.get("reputation_data");
        if (reputationData == null) {
            return 0;
        }
        
        // Awards and recognition
        if ("true".equals(reputationData.get("has_awards"))) {
            score += 15;
        }
        
        // Customer reviews and testimonials
        if ("true".equals(reputationData.get("has_reviews"))) {
            score += 10;
        }
        
        // Industry membership
        if ("true".equals(reputationData.get("industry_membership"))) {
            score += 10;
        }
        
        return Math.min(score, 25); // Cap reputation score at 25 points
    }
    
    /**
     * Analyzes regulatory compliance based on extracted data.
     * @param extractedData Data extracted from documents
     * @param rules Validation rules
     * @return Compliance score
     */
    private int analyzeCompliance(Map<String, Object> extractedData, List<ValidationRule> rules) {
        int score = 0;
        
        @SuppressWarnings("unchecked")
        Map<String, String> complianceData = (Map<String, String>) extractedData.get("compliance_data");
        if (complianceData == null) {
            return 0;
        }
        
        // ISO certifications
        if (complianceData.get("iso_certifications") != null) {
            score += 20;
        }
        
        // Other certifications
        if ("true".equals(complianceData.get("has_certifications"))) {
            score += 10;
        }
        
        // Regulatory compliance
        if (complianceData.get("regulatory_compliance") != null) {
            score += 15;
        }
        
        return Math.min(score, 25); // Cap compliance score at 25 points
    }
    
    /**
     * Analyzes profile completeness.
     * @param vendorData Vendor data from Laravel
     * @return Profile completeness score
     */
    private int analyzeProfileCompleteness(VendorData vendorData) {
        int score = 0;
        
        if (vendorData.getCompany() != null && !vendorData.getCompany().isEmpty()) {
            score += 5;
        }
        
        if (vendorData.getAddress() != null && !vendorData.getAddress().isEmpty()) {
            score += 5;
        }
        
        if (vendorData.getPhone() != null && !vendorData.getPhone().isEmpty()) {
            score += 5;
        }
        
        return score; // Profile completeness can add up to 15 points
    }
    
    /**
     * Validates a single file's format based on its extension.
     * @param filePath The path of the file to validate
     * @param allowedFormats A comma-separated string of allowed extensions
     * @return True if the file format is allowed, false otherwise
     */
    private boolean validateFileFormat(String filePath, String allowedFormats) {
        String[] allowedExtensions = allowedFormats.toLowerCase().split(",");
        String fileExtension = "";
        int lastDotIndex = filePath.lastIndexOf('.');
        
        if (lastDotIndex > 0) {
            fileExtension = filePath.substring(lastDotIndex + 1).toLowerCase();
        }
        return Arrays.asList(allowedExtensions).contains(fileExtension);
    }
}
