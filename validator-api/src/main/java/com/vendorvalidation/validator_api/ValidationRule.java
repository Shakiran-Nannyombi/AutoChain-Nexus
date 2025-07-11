package com.vendorvalidation.validator_api;

// This class must match the structure of the data sent from Laravel.
public class ValidationRule {
    private String name;
    private String category;
    private String description;
    private String value; // This is the threshold
    private String status;
    private String currency; // Currency for financial rules (e.g., 'shs', 'usd', etc.)

    // Getters
    public String getName() { return name; }
    public String getCategory() { return category; }
    public String getDescription() { return description; }
    public String getValue() { return value; }
    public String getStatus() { return status; }
    public String getCurrency() { return currency; }

    // Setters
    public void setName(String name) { this.name = name; }
    public void setCategory(String category) { this.category = category; }
    public void setDescription(String description) { this.description = description; }
    public void setValue(String value) { this.value = value; }
    public void setStatus(String status) { this.status = status; }
    public void setCurrency(String currency) { this.currency = currency; }
} 