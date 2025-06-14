# Lab 3: Form Validation and Error Handling

## Overview
Lab 3 focuses on implementing comprehensive form validation and error handling techniques to create a more robust and user-friendly library management system. This lab builds upon the improved UI and database relationships established in Lab 2, adding layers of data validation and providing meaningful feedback to users.

## Files Structure
- `index.php`: Main entry point with navigation to other sections
- `add_book.php`: Book addition form with enhanced validation
- `edit_book.php`: Book editing form with validation
- `view_books.php`: Book listing with improved error handling
- `add_author.php`: Author addition form with validation
- `validation.php`: Centralized validation functions
- `error_handler.php`: Custom error handling functions
- `db_config.php`: Database configuration
- `styles.css`: CSS styling for forms and error messages

## Key Features

### 1. Client-Side Validation
- JavaScript form validation for immediate feedback
- Field validation before form submission
- Custom validation messages for different input types
- Visual indicators for valid/invalid fields

### 2. Server-Side Validation
- Comprehensive PHP validation for all form inputs
- Type checking and format validation
- Required field validation
- Range and length validation for numeric and text fields
- Protection against common input vulnerabilities

### 3. Error Handling
- Custom error handler for PHP errors
- Graceful handling of database connection failures
- Detailed error logging with timestamps
- User-friendly error messages without exposing system details
- Consistent error presentation across the application

### 4. Form Features
- Persistent form data after validation errors
- Clear indication of required fields
- Tooltips with input requirements
- Auto-formatting for certain fields (e.g., dates, currency)
- Field-specific validation rules

### 5. Security Enhancements
- Input sanitization to prevent XSS attacks
- Prepared statements for all database operations
- CSRF token implementation for form submissions
- Validation against allowed values for select fields

## Validation Rules Implemented

### Book Form Validation
- **Title**: Required, 2-200 characters, no special HTML characters
- **Author**: Required, must exist in database or be added
- **Publication Year**: Required, numeric, between 1000 and current year
- **Genre**: Required, must exist in database
- **Price**: Required, numeric, positive value with up to 2 decimal places
- **ISBN**: Optional, must follow ISBN-10 or ISBN-13 format with checksum validation

### Author Form Validation
- **Name**: Required, 2-100 characters, alphabetic with spaces
- **Birth Year**: Optional, numeric, between 1000 and current year
- **Nationality**: Optional, 2-50 characters, alphabetic with spaces
- **Biography**: Optional, maximum 1000 characters

## Error Handling Implementation

### Types of Errors Handled
- **Validation Errors**: Form input doesn't meet requirements
- **Database Errors**: Connection issues, query failures
- **System Errors**: PHP errors, file access issues
- **Logic Errors**: Inconsistent application state

### Error Presentation
- **Inline Errors**: Displayed next to the relevant form field
- **Summary Errors**: Collected at the top of the form
- **System Errors**: Logged to file and displayed as generic message
- **Success Messages**: Confirmation of successful operations

## Code Example: Validation Function

```php
/**
 * Validates book form data
 * @param array $data Form data to validate
 * @return array Array of error messages (empty if no errors)
 */
function validateBookForm($data) {
    $errors = [];
    $current_year = date('Y');
    
    // Validate title
    if (empty($data['title'])) {
        $errors['title'] = 'Book title is required';
    } elseif (strlen($data['title']) < 2 || strlen($data['title']) > 200) {
        $errors['title'] = 'Book title must be between 2 and 200 characters';
    }
    
    // Validate author
    if (empty($data['author_id'])) {
        $errors['author_id'] = 'Author is required';
    }
    
    // Validate publication year
    if (empty($data['publication_year'])) {
        $errors['publication_year'] = 'Publication year is required';
    } elseif (!is_numeric($data['publication_year'])) {
        $errors['publication_year'] = 'Publication year must be a number';
    } elseif ($data['publication_year'] < 1000 || $data['publication_year'] > $current_year) {
        $errors['publication_year'] = "Publication year must be between 1000 and $current_year";
    }
    
    // Validate price
    if (empty($data['price'])) {
        $errors['price'] = 'Price is required';
    } elseif (!is_numeric($data['price'])) {
        $errors['price'] = 'Price must be a number';
    } elseif ($data['price'] < 0) {
        $errors['price'] = 'Price cannot be negative';
    }
    
    return $errors;
}
```

## How to Use

1. **Setup Environment**:
   - Ensure your web server environment is running
   - Configure PHP error reporting for development

2. **Database Setup**:
   - Use the same database structure as Lab 2
   - Update connection details in `db_config.php`

3. **Access the Application**:
   - Navigate to `http://localhost/Lab3/index.php`
   - Use the navigation to access different sections

4. **Testing Validation**:
   - Try submitting forms with invalid data to see validation in action
   - Test different input combinations to verify all validation rules
   - Check error messages for clarity and helpfulness

## Learning Objectives
- Implementing client-side and server-side validation
- Creating reusable validation functions
- Handling and displaying form errors effectively
- Implementing custom error handling
- Maintaining form state after validation failures
- Securing forms against common vulnerabilities
- Creating user-friendly error messages

## Best Practices Implemented
- Separation of validation logic from display code
- Consistent error message format
- Validation on both client and server sides
- Sanitization of all user inputs
- Prepared statements for database queries
- Graceful error handling with user-friendly messages
- Detailed error logging for debugging

## Next Steps
After completing Lab 3, proceed to Lab 4 which introduces object-oriented programming concepts to refactor the application for better organization and maintainability.