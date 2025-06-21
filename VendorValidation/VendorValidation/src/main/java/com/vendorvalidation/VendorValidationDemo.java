package com.vendorvalidation;

import java.io.File;
import java.io.IOException;

/**
 * Demo application for the vendor validation system.
 * This class demonstrates how to use the VendorValidator class.
 */
public class VendorValidationDemo {
    
    public static void main(String[] args) {
        System.out.println("Vendor Validation System Demo");
        System.out.println("-----------------------------");
        
        // Create a new VendorValidator
        VendorValidator validator = new VendorValidator();
        
        // Create a sample PDF file (in a real application, this would be a real PDF)
        File samplePdf = new File("sample_vendor.pdf");
        
        try {
            // Validate the vendor
            System.out.println("\nValidating vendor from PDF...");
            String result = validator.validateVendorPDF(samplePdf);
            
            // Display the result
            System.out.println("\nValidation Result: " + result);
            
        } catch (IOException e) {
            System.err.println("Error validating vendor: " + e.getMessage());
            e.printStackTrace();
        }
    }
}
