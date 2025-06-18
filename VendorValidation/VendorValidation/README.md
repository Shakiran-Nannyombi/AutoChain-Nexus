# Vendor Validation System

A Java application for validating vendor information from PDF documents based on financial, reputation, and regulatory compliance criteria.

## Overview

This system extracts data from vendor PDF documents and performs analysis on three key areas:
1. Financial health (score 0-100)
2. Reputation (score 0-100)
3. Regulatory compliance (pass/fail)

A vendor is considered validated if they meet the following criteria:
- Financial score ≥ 70
- Reputation score ≥ 60
- Compliance check passes

## Project Structure

- `VendorValidator.java`: Main class containing the validation logic
- `VendorData.java`: Data model for storing vendor information
- `VendorValidationDemo.java`: Demo application showing how to use the system

## Requirements

- Java 11 or higher
- Maven for dependency management

## Dependencies

- Apache PDFBox: For PDF text extraction
- JUnit: For unit testing

## Building the Project

```bash
mvn clean package
```

This will create a JAR file with dependencies in the `target` directory.

## Running the Demo

```bash
java -jar target/vendor-validation-1.0-SNAPSHOT-jar-with-dependencies.jar
```

## Extending the System

To use this system with real PDF files:

1. Place your vendor PDF files in a known location
2. Update the file path in the demo application
3. Implement more sophisticated analysis algorithms as needed

## Future Enhancements

- Add a GUI for easier interaction
- Implement database storage for validation results
- Add reporting capabilities
- Integrate with external APIs for more comprehensive vendor checks
