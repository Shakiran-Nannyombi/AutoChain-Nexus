package com.vendorvalidation.validator_api;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.apache.tika.Tika;
import org.apache.tika.metadata.Metadata;
import org.apache.tika.parser.AutoDetectParser;
import org.apache.tika.parser.ParseContext;
import org.apache.tika.parser.Parser;
import org.apache.tika.sax.BodyContentHandler;

import java.io.File;
import java.io.FileInputStream;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * PDFProcessor class for extracting and analyzing data from PDF documents.
 * This class handles financial statements, compliance documents, and reputation data.
 */
public class DocProcessor {
    
    @SuppressWarnings("unused")
    private final Tika tika = new Tika(); // Tika is a library for detecting the type of a file
    
    /**
     * Extracts text content from a PDF file.
     * @param filePath Path to the PDF file
     * @return Extracted text content
     */
    public String extractText(String filePath) {
        try {
            File file = new File(filePath);
            if (!file.exists()) {
                return "";
            }
            
            // Try PDFBox first for PDFs
            if (filePath.toLowerCase().endsWith(".pdf")) {
                try (PDDocument document = PDDocument.load(file)) {
                    PDFTextStripper stripper = new PDFTextStripper();
                    String text = stripper.getText(document);
                    System.out.println("DEBUG: Extracted text (first 500 chars): " + text.substring(0, Math.min(500, text.length())));
                    return text;
                }
            }
            
            // Fallback to Tika for other document types
            try (FileInputStream inputStream = new FileInputStream(file)) {
                BodyContentHandler handler = new BodyContentHandler(-1);
                Metadata metadata = new Metadata();
                ParseContext context = new ParseContext();
                Parser parser = new AutoDetectParser();
                parser.parse(inputStream, handler, metadata, context);
                return handler.toString();
            }
        } catch (Exception e) {
            System.err.println("Error extracting text from " + filePath + ": " + e.getMessage());
            return "";
        }
    }
    
    /**
     * Analyzes financial data from extracted text.
     * @param text Extracted text content
     * @return Map containing financial metrics
     */
    public Map<String, String> analyzeFinancialData(String text) {
        Map<String, String> financialData = new HashMap<>();
        
        // Extract revenue information
        Pattern revenuePattern = Pattern.compile("(?i)(revenue|sales|income|turnover)\\s*:?\\s*\\$?([\\d,]+(?:\\.\\d{2})?)");
        Matcher revenueMatcher = revenuePattern.matcher(text);
        if (revenueMatcher.find()) {
            financialData.put("revenue", revenueMatcher.group(2).replace(",", ""));
        }
        
        // Extract profit information
        Pattern profitPattern = Pattern.compile("(?i)(profit|net income|earnings)\\s*:?\\s*\\$?([\\d,]+(?:\\.\\d{2})?)");
        Matcher profitMatcher = profitPattern.matcher(text);
        if (profitMatcher.find()) {
            financialData.put("profit", profitMatcher.group(2).replace(",", ""));
        }
        
        // Extract years in business
        Pattern yearsPattern = Pattern.compile("(?i)(established|founded|since|in business)\\s*(?:in|since)?\\s*(19\\d{2}|20\\d{2})");
        Matcher yearsMatcher = yearsPattern.matcher(text);
        if (yearsMatcher.find()) {
            int yearFounded = Integer.parseInt(yearsMatcher.group(2));
            int currentYear = java.time.Year.now().getValue();
            int yearsInBusiness = currentYear - yearFounded;
            financialData.put("years_in_business", String.valueOf(yearsInBusiness));
        }
        
        // Extract employee count
        Pattern employeesPattern = Pattern.compile("(?i)(employees|staff|team)\\s*:?\\s*(\\d+)");
        Matcher employeesMatcher = employeesPattern.matcher(text);
        if (employeesMatcher.find()) {
            financialData.put("employee_count", employeesMatcher.group(2));
        }
        
        return financialData;
    }
    
    /**
     * Analyzes compliance and certification data from extracted text.
     * @param text Extracted text content
     * @return Map containing compliance information
     */
    public Map<String, String> analyzeComplianceData(String text) {
        Map<String, String> complianceData = new HashMap<>();
        
        // Check for ISO certifications
        Pattern isoPattern = Pattern.compile("(?i)(ISO\\s*\\d{4}|ISO\\s*\\d{5})");
        Matcher isoMatcher = isoPattern.matcher(text);
        if (isoMatcher.find()) {
            complianceData.put("iso_certifications", isoMatcher.group(1));
        }
        
        // Check for other certifications
        Pattern certPattern = Pattern.compile("(?i)(certified|certification|accredited|license|permit)");
        Matcher certMatcher = certPattern.matcher(text);
        if (certMatcher.find()) {
            complianceData.put("has_certifications", "true");
        }
        
        // Check for regulatory compliance
        Pattern regPattern = Pattern.compile("(?i)(FDA|EPA|OSHA|DOT|compliance|regulatory)");
        Matcher regMatcher = regPattern.matcher(text);
        if (regMatcher.find()) {
            complianceData.put("regulatory_compliance", regMatcher.group(1));
        }
        
        return complianceData;
    }
    
    /**
     * Analyzes reputation and business standing data from extracted text.
     * @param text Extracted text content
     * @return Map containing reputation information
     */
    public Map<String, String> analyzeReputationData(String text) {
        Map<String, String> reputationData = new HashMap<>();
        
        // Check for awards and recognition
        Pattern awardsPattern = Pattern.compile("(?i)(award|recognition|honor|excellence|best|top)");
        Matcher awardsMatcher = awardsPattern.matcher(text);
        if (awardsMatcher.find()) {
            reputationData.put("has_awards", "true");
        }
        
        // Check for customer testimonials or reviews
        Pattern reviewsPattern = Pattern.compile("(?i)(testimonial|review|customer|client|satisfaction)");
        Matcher reviewsMatcher = reviewsPattern.matcher(text);
        if (reviewsMatcher.find()) {
            reputationData.put("has_reviews", "true");
        }
        
        // Check for industry associations
        Pattern industryPattern = Pattern.compile("(?i)(association|chamber|federation|guild|society)");
        Matcher industryMatcher = industryPattern.matcher(text);
        if (industryMatcher.find()) {
            reputationData.put("industry_membership", "true");
        }
        
        return reputationData;
    }
    
    /**
     * Processes a document and extracts all relevant data.
     * @param filePath Path to the document
     * @return Map containing all extracted data
     */
    public Map<String, Object> processDocument(String filePath) {
        Map<String, Object> result = new HashMap<>();
        
        String text = extractText(filePath);
        if (text.isEmpty()) {
            return result;
        }
        
        result.put("financial_data", analyzeFinancialData(text));
        result.put("compliance_data", analyzeComplianceData(text));
        result.put("reputation_data", analyzeReputationData(text));
        result.put("text_length", text.length());
        
        return result;
    }
} 