package com.vendorvalidation;

import java.util.HashMap;
import java.util.Map;

/**
 * VendorData class for storing vendor information extracted from PDF files.
 * This class contains all the data needed for vendor validation.
 */
public class VendorData {
    private String vendorId;
    private String name;
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
    public String getVendorId() {
        return vendorId;
    }
    
    /**
     * Sets the vendor ID.
     * 
     * @param vendorId The vendor ID to set
     */
    public void setVendorId(String vendorId) {
        this.vendorId = vendorId;
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