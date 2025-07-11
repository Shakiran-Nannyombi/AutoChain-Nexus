package com.vendorvalidation.validator_api;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * VendorData class for storing vendor information extracted from PDF files.
 * This class contains all the data needed for vendor validation.
 */
public class VendorData {
    private long id;
    private String name;
    private String email;
    private String company;
    private String phone;
    private String address;
    private String currency; // Currency for financial data
    private List<String> documentPaths;
    private Map<String, String> financialData;
    private Map<String, String> reputationData;
    private Map<String, String> complianceData;
    
    /**
     * Default constructor.
     */
    public VendorData() {
        this.financialData = new HashMap<>();
        this.reputationData = new HashMap<>();
        this.complianceData = new HashMap<>();
    }
    
    /**
     * Gets the vendor ID.
     * 
     * @return The vendor ID
     */
    public long getId() {
        return id;
    }
    
    /**
     * Sets the vendor ID.
     * 
     * @param id The vendor ID to set
     */
    public void setId(long id) {
        this.id = id;
    }
    
    /**
     * Gets the vendor name.
     * 
     * @return The vendor name
     */
    public String getName() {
        return name;
    }
    
    /**
     * Sets the vendor name.
     * 
     * @param name The vendor name to set
     */
    public void setName(String name) {
        this.name = name;
    }
    
    /**
     * Gets the email.
     * 
     * @return The email
     */
    public String getEmail() {
        return email;
    }
    
    /**
     * Sets the email.
     * 
     * @param email The email to set
     */
    public void setEmail(String email) {
        this.email = email;
    }
    
    /**
     * Gets the company.
     * 
     * @return The company
     */
    public String getCompany() {
        return company;
    }
    
    /**
     * Sets the company.
     * 
     * @param company The company to set
     */
    public void setCompany(String company) {
        this.company = company;
    }
    
    /**
     * Gets the phone.
     * 
     * @return The phone
     */
    public String getPhone() {
        return phone;
    }
    
    /**
     * Sets the phone.
     * 
     * @param phone The phone to set
     */
    public void setPhone(String phone) {
        this.phone = phone;
    }
    
    /**
     * Gets the address.
     * 
     * @return The address
     */
    public String getAddress() {
        return address;
    }
    
    /**
     * Sets the address.
     * 
     * @param address The address to set
     */
    public void setAddress(String address) {
        this.address = address;
    }
    
    /**
     * Gets the currency.
     * 
     * @return The currency
     */
    public String getCurrency() {
        return currency;
    }
    
    /**
     * Sets the currency.
     * 
     * @param currency The currency to set
     */
    public void setCurrency(String currency) {
        this.currency = currency;
    }
    
    /**
     * Gets the document paths.
     * 
     * @return A list of document paths
     */
    public List<String> getDocumentPaths() {
        return documentPaths;
    }
    
    /**
     * Sets the document paths.
     * 
     * @param documentPaths The document paths to set
     */
    public void setDocumentPaths(List<String> documentPaths) {
        this.documentPaths = documentPaths;
    }
    
    /**
     * Gets the financial data.
     * 
     * @return A map of financial data
     */
    public Map<String, String> getFinancialData() {
        return financialData;
    }
    
    /**
     * Sets the financial data.
     * 
     * @param financialData The financial data to set
     */
    public void setFinancialData(Map<String, String> financialData) {
        this.financialData = financialData;
    }
    
    /**
     * Gets the reputation data.
     * 
     * @return A map of reputation data
     */
    public Map<String, String> getReputationData() {
        return reputationData;
    }
    
    /**
     * Sets the reputation data.
     * 
     * @param reputationData The reputation data to set
     */
    public void setReputationData(Map<String, String> reputationData) {
        this.reputationData = reputationData;
    }
    
    /**
     * Gets the compliance data.
     * 
     * @return A map of compliance data
     */
    public Map<String, String> getComplianceData() {
        return complianceData;
    }
    
    /**
     * Sets the compliance data.
     * 
     * @param complianceData The compliance data to set
     */
    public void setComplianceData(Map<String, String> complianceData) {
        this.complianceData = complianceData;
    }
}