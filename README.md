# Library Management System - Complete Lab Series

## Overview
This repository contains a comprehensive series of six progressive labs that build a complete Library Management System using PHP and MySQL. Each lab focuses on different aspects of web development, gradually transforming a simple CRUD application into a professional, secure, and feature-rich web application with API capabilities.

## Lab Structure

### Lab 1: Basic CRUD Operations
- **Focus**: Introduction to PHP and MySQL fundamentals
- **Key Features**: 
  - Database connection setup
  - Basic Create, Read, Update, Delete operations
  - Simple HTML forms and data display
  - Basic error handling
- **Files**: `db_connect.php`, `read_books.php`, `create_book.php`, `update_book.php`, `delete_book.php`
- **Skills Learned**: PHP basics, MySQL queries, HTML forms, basic error handling

### Lab 2: Improved UI and Database Relationships
- **Focus**: Enhanced user interface and proper database design
- **Key Features**:
  - Normalized database structure with relationships
  - Foreign key constraints
  - Improved navigation and UI
  - Filtering and search functionality
- **Files**: `index.php`, `add_book.php`, `view_books.php`, `db_config.php`, `functions.php`
- **Skills Learned**: Database normalization, JOIN queries, CSS styling, code organization

### Lab 3: Form Validation and Error Handling
- **Focus**: Robust data validation and error management
- **Key Features**:
  - Client-side and server-side validation
  - Custom error handling
  - Input sanitization
  - User-friendly error messages
  - CSRF protection
- **Files**: `validation.php`, `error_handler.php`, enhanced form files
- **Skills Learned**: Form validation techniques, error handling, security practices

### Lab 4: Object-Oriented Programming Implementation
- **Focus**: Refactoring to OOP architecture
- **Key Features**:
  - Class hierarchy and inheritance
  - Encapsulation and data protection
  - Polymorphism and abstraction
  - Design patterns implementation
- **Files**: `Book.php`, `Ebook.php`, `Member.php`, `BookLoan.php`, etc.
- **Skills Learned**: OOP principles, inheritance, encapsulation, design patterns

### Lab 5: Complete Web Application with Authentication and AJAX
- **Focus**: Professional web application development
- **Key Features**:
  - User authentication system
  - Role-based access control
  - AJAX for dynamic content
  - Responsive design
  - Advanced book management
  - Security features
- **Files**: MVC-like structure with classes, views, and includes directories
- **Skills Learned**: Authentication, AJAX, responsive design, security practices

### Lab 6: Advanced Security, API Development, and Deployment
- **Focus**: Production-ready enhancements
- **Key Features**:
  - Advanced security implementations
  - RESTful API development
  - JWT authentication
  - Deployment considerations
  - Performance optimization
- **Files**: Security components, API endpoints, deployment guides
- **Skills Learned**: Advanced security, API development, deployment strategies

## Database Evolution
The database schema evolves throughout the labs:
- **Lab 1**: Simple Books table
- **Lab 2**: Normalized structure with Authors, Genres, and Books tables
- **Lab 3**: Same structure with additional validation
- **Lab 4**: Enhanced schema supporting OOP model
- **Lab 5**: Comprehensive schema with Users, Books, BookLoans, and more
- **Lab 6**: Additional security and API-related tables

## Installation and Setup

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher / MariaDB 10.3+
- Web server (Apache, Nginx, etc.)
- Required PHP extensions: PDO, GD, FileInfo

### Basic Setup Steps
1. Clone or download this repository
2. Set up a local web server environment (XAMPP, WAMP, MAMP, etc.)
3. Create the database using the SQL scripts provided in each lab
4. Update database connection details in the configuration files
5. Access the application through your web browser

### Lab-Specific Setup
Each lab has its own README file with detailed setup instructions specific to that lab's requirements.

## Learning Path
This lab series is designed as a progressive learning path:

1. **Start with Lab 1** to understand the basics of PHP and MySQL
2. **Progress to Lab 2** to learn about database relationships and UI improvements
3. **Continue to Lab 3** to master form validation and error handling
4. **Move to Lab 4** to understand object-oriented programming principles
5. **Advance to Lab 5** to build a complete web application with authentication
6. **Complete Lab 6** to implement advanced security, API features, and deployment

## Security Features
The final application includes numerous security features:
- Password hashing with bcrypt
- CSRF protection
- XSS prevention
- SQL injection protection
- Input validation and sanitization
- Rate limiting
- Secure HTTP headers
- JWT-based API authentication

## API Documentation
The RESTful API developed in Lab 6 provides endpoints for:
- Book management (GET, POST, PUT, DELETE)
- User management (GET, POST, PUT)
- Loan operations (GET, POST, PUT)

Detailed API documentation is available in `api/documentation.php`.

## Deployment
Deployment guidance is provided in Lab 6, covering:
- Local network deployment
- Shared hosting considerations
- Security configurations for production
- Performance optimization tips



## License
This project is available for educational use. Please check individual lab README files for specific licensing information.