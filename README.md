# 📚 Library Management System - Complete Lab Series

<<<<<<< HEAD
## 🎥 Demonstration Videos
Demonstration videos have been added to show the implementation and functionality of:
- Lab 2: 
- Lab 3: 
- Lab 4: 
- Lab 5: 
- Lab 6: Security

=======
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
## 🌟 Overview
This repository contains a comprehensive series of **six progressive labs** that build a complete Library Management System using **PHP and MySQL**. Each lab focuses on different aspects of web development, gradually transforming a simple CRUD application into a professional, secure, and feature-rich web application with API capabilities.

The project demonstrates the evolution from basic procedural programming to advanced object-oriented architecture, incorporating modern web development practices, security measures, and deployment strategies.

## 🎯 Learning Objectives
By completing this lab series, you will master:
- **PHP fundamentals** and advanced concepts
- **MySQL database design** and optimization
- **Object-Oriented Programming** principles
- **Web security** best practices
- **API development** and integration
- **Authentication and authorization** systems
- **AJAX and responsive design**
- **Deployment and hosting** strategies

## 📋 Lab Structure

### 🔰 Lab 1: Basic CRUD Operations
**Foundation Building - Introduction to PHP and MySQL**

- **🎯 Focus**: Introduction to PHP and MySQL fundamentals
- **⭐ Key Features**:
  - Database connection setup with MySQLi
  - Basic Create, Read, Update, Delete operations
  - Simple HTML forms and data display
  - Basic error handling and debugging
  - Form data processing with $_POST
  - SQL query execution and result handling
- **📁 Files**:
  - `db_connect.php` - Database connection configuration
  - `read_books.php` - Display all books with formatted table
  - `create_book.php` - Add new books with form validation
  - `update_book.php` - Edit existing book information
  - `delete_book.php` - Remove books with confirmation
  - `styles.css` - Basic styling for forms and tables
- **🎓 Skills Learned**:
  - PHP syntax and superglobals ($_POST, $_GET)
  - MySQL queries (SELECT, INSERT, UPDATE, DELETE)
  - HTML forms and input handling
  - Basic error handling with try-catch
  - CSS styling for web forms
- **💾 Database Schema**:
  ```sql
  CREATE DATABASE LibraryDB;
  CREATE TABLE Books (
      book_id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      author VARCHAR(255) NOT NULL,
      publication_year INT,
      genre VARCHAR(100),
      price DECIMAL(10, 2)
  );
  ```

### 🎨 Lab 2: Improved UI and Database Relationships
**Database Normalization and Enhanced User Experience**

- **🎯 Focus**: Enhanced user interface and proper database design
- **⭐ Key Features**:
  - Normalized database structure with relationships
  - Foreign key constraints and referential integrity
  - Improved navigation and responsive UI
  - Filtering and search functionality
  - Dropdown menus populated from database
  - JOIN queries for related data display
- **📁 Files**:
  - **Exercise 1**: Basic UI improvements
    - `index.php` - Enhanced landing page
    - `styles.css` - Comprehensive CSS styling
  - **Exercise 2**: Database relationships
    - `add_book.php` - Form with author/genre selection
    - `view_books.php` - Enhanced listing with filters
    - `db_config.php` - Improved database configuration
    - `functions.php` - Reusable utility functions
- **🎓 Skills Learned**:
  - Database normalization (1NF, 2NF, 3NF)
  - JOIN queries (INNER, LEFT, RIGHT)
  - CSS Grid and Flexbox layouts
  - Code organization and modularity
  - Dynamic form population
- **💾 Database Schema**:
  ```sql
  CREATE DATABASE LibraryDB2;
  CREATE TABLE Authors (
      author_id INT AUTO_INCREMENT PRIMARY KEY,
      author_name VARCHAR(255) NOT NULL,
      birth_year INT,
      nationality VARCHAR(100)
  );
  CREATE TABLE Genres (
      genre_id INT AUTO_INCREMENT PRIMARY KEY,
      genre_name VARCHAR(100) NOT NULL
  );
  CREATE TABLE Books (
      book_id INT AUTO_INCREMENT PRIMARY KEY,
      book_title VARCHAR(255) NOT NULL,
      author_id INT,
      publication_year INT,
      genre_id INT,
      price DECIMAL(10, 2),
      FOREIGN KEY (author_id) REFERENCES Authors(author_id),
      FOREIGN KEY (genre_id) REFERENCES Genres(genre_id)
  );
  ```

### 🛡️ Lab 3: Form Validation and Error Handling
**Robust Data Validation and Security Foundations**

- **🎯 Focus**: Robust data validation and error management
- **⭐ Key Features**:
  - Client-side and server-side validation
  - Custom error handling with detailed logging
  - Input sanitization and XSS prevention
  - User-friendly error messages
  - CSRF protection implementation
  - Form state persistence after errors
- **📁 Files**:
  - `validation.php` - Centralized validation functions
  - `error_handler.php` - Custom error handling system
  - `add_book.php` - Enhanced form with validation
  - `edit_book.php` - Book editing with error handling
  - `add_author.php` - Author management with validation
  - Enhanced form files with JavaScript validation
- **🎓 Skills Learned**:
  - Input validation techniques (regex, type checking)
  - Error handling and logging strategies
  - Security practices (XSS, CSRF protection)
  - JavaScript form validation
  - User experience optimization
- **🔒 Security Features**:
  - Input sanitization with `htmlspecialchars()`
  - Prepared statements for SQL injection prevention
  - CSRF token implementation
  - Client and server-side validation
  - Error logging without exposing sensitive data

### 🏗️ Lab 4: Object-Oriented Programming Implementation
**Modern PHP Architecture and Design Patterns**

- **🎯 Focus**: Refactoring to OOP architecture
- **⭐ Key Features**:
  - Class hierarchy and inheritance
  - Encapsulation and data protection
  - Polymorphism and abstraction
  - Design patterns implementation
  - Factory pattern for object creation
  - Repository pattern for data access
- **📁 Files Structure**:
  - **Exercise 1**: Basic OOP
    - `Book.php` - Base Book class
    - `create_book.php` - OOP-based book creation
    - `view_books.php` - Object-oriented book listing
    - `BookManager.php` - Book operations handler
  - **Exercise 2**: Advanced OOP
    - `AbstractBook.php` - Abstract base class
    - `PhysicalBook.php` - Physical book implementation
    - `Ebook.php` - Electronic book with special features
    - `BookFactory.php` - Factory pattern implementation
  - **Exercise 3 & 4**: Complete OOP System
    - `Book.php` - Enhanced Book class
    - `Ebook.php` - E-book with discount features
    - `Member.php` - Library member management
    - `BookLoan.php` - Loan tracking system
    - `library_test.php` - Main application interface
- **🎓 Skills Learned**:
  - OOP principles (Encapsulation, Inheritance, Polymorphism)
  - Design patterns (Factory, Singleton, Repository)
  - Class relationships and UML design
  - Code reusability and maintainability
  - Abstract classes and interfaces
- **💾 Database Schema**:
  ```sql
  CREATE DATABASE LibraryDB4;
  CREATE TABLE Books (
      book_id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      author VARCHAR(255) NOT NULL,
      price DECIMAL(10, 2) NOT NULL,
      genre VARCHAR(100),
      year INT,
      is_borrowed BOOLEAN DEFAULT FALSE,
      is_ebook BOOLEAN DEFAULT FALSE
  );
  CREATE TABLE Members (
      member_id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      join_date DATE NOT NULL,
      status VARCHAR(50) DEFAULT 'active'
  );
  CREATE TABLE BookLoans (
      loan_id INT AUTO_INCREMENT PRIMARY KEY,
      book_id INT NOT NULL,
      member_id INT NOT NULL,
      loan_date DATE NOT NULL,
      due_date DATE NOT NULL,
      return_date DATE,
      FOREIGN KEY (book_id) REFERENCES Books(book_id),
      FOREIGN KEY (member_id) REFERENCES Members(member_id)
  );
  ```

### 🚀 Lab 5: Complete Web Application with Authentication and AJAX
**Professional-Grade Application with Modern Features**

- **🎯 Focus**: Professional web application development
- **⭐ Key Features**:
  - User authentication system with bcrypt hashing
  - Role-based access control (Admin, Librarian, Member)
  - AJAX for dynamic content loading
  - Responsive design with mobile-first approach
  - Advanced book management with file uploads
  - Security features and activity logging
  - Google OAuth integration
  - Email notifications and password reset
- **📁 Files Structure**:
  - **Core Application**:
    - `index.php` - Entry point and login page
    - `dashboard.php` - Main application dashboard
    - `login.php` - Authentication handler
    - `register.php` - User registration system
    - `logout.php` - Session termination
  - **Includes Directory**:
    - `includes/db_connect.php` - PDO database connection
    - `includes/functions.php` - Utility functions
    - `includes/auth.php` - Authentication functions
    - `includes/header.php` - Common header template
    - `includes/sidebar.php` - Navigation sidebar
  - **Views Directory**:
    - `views/dashboard_home_content.php` - Dashboard overview
    - `views/available_books_content.php` - Book catalog
    - `views/my_borrowed_books_content.php` - User loans
    - `views/manage_all_books_content.php` - Admin book management
    - `views/add_book_form_content.php` - Book addition form
    - `views/profile_content.php` - User profile management
  - **Classes Directory**:
    - `classes/User.php` - User authentication and management
    - `classes/Book.php` - Enhanced Book class
    - `classes/BookLoan.php` - Loan management system
    - `classes/Database.php` - PDO wrapper class
  - **Assets**:
    - `css/style.css` - Main stylesheet
    - `js/main.js` - Core JavaScript functionality
    - `js/ajax.js` - AJAX request handling
- **🎓 Skills Learned**:
  - Session management and authentication
  - Role-based access control implementation
  - AJAX and dynamic content loading
  - Responsive web design principles
  - File upload and image handling
  - Email integration and notifications
  - Google OAuth implementation
- **💾 Database Schema**:
  ```sql
  CREATE DATABASE LibraryDB5;
  CREATE TABLE Users (
      user_id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(50) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      full_name VARCHAR(100) NOT NULL,
      role ENUM('admin', 'librarian', 'member') NOT NULL DEFAULT 'member',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      last_login TIMESTAMP NULL,
      status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'
  );
  CREATE TABLE Books (
      book_id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      author VARCHAR(255) NOT NULL,
      isbn VARCHAR(20) UNIQUE,
      publication_year INT,
      publisher VARCHAR(100),
      genre VARCHAR(50),
      description TEXT,
      cover_image VARCHAR(255),
      price DECIMAL(10, 2) NOT NULL,
      quantity INT NOT NULL DEFAULT 1,
      is_ebook BOOLEAN DEFAULT FALSE,
      file_path VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
  CREATE TABLE BookLoans (
      loan_id INT AUTO_INCREMENT PRIMARY KEY,
      book_id INT NOT NULL,
      user_id INT NOT NULL,
      loan_date DATE NOT NULL,
      due_date DATE NOT NULL,
      return_date DATE NULL,
      status ENUM('active', 'returned', 'overdue') DEFAULT 'active',
      FOREIGN KEY (book_id) REFERENCES Books(book_id),
      FOREIGN KEY (user_id) REFERENCES Users(user_id)
  );
  CREATE TABLE Notifications (
      notification_id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      message TEXT NOT NULL,
      is_read BOOLEAN DEFAULT FALSE,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
  ```

### 🔐 Lab 6: Advanced Security, API Development, and Deployment
**Production-Ready Security and API Integration**

- **🎯 Focus**: Production-ready enhancements and security
- **⭐ Key Features**:
  - Advanced security implementations
  - RESTful API development with JWT
  - CSRF protection with token validation
  - XSS and SQL injection prevention
  - Security vulnerability testing
  - Deployment guides for local networks
  - Performance optimization techniques
  - API documentation and testing tools
- **📁 Files Structure**:
  - **Security Components**:
    - `csrf_token.php` - Comprehensive CSRF protection
    - `test_security.php` - Security vulnerability testing
  - **API Components**:
    - `api/index.php` - API entry point with routing
    - `api/books.php` - Book-related API endpoints
    - `api/users.php` - User management API
    - `api/loans.php` - Loan operations API
    - `api/auth.php` - JWT authentication system
  - **Deployment**:
    - `hosting_guide.txt` - Local network deployment guide
    - Configuration files for production deployment
- **🎓 Skills Learned**:
  - Advanced security implementation
  - RESTful API design and development
  - JWT token-based authentication
  - Security testing and vulnerability assessment
  - Production deployment strategies
  - Performance optimization techniques
- **🔒 Advanced Security Features**:
  - CSRF token validation with expiry
  - XSS prevention with output escaping
  - SQL injection protection with prepared statements
  - Rate limiting and brute force protection
  - Secure HTTP headers implementation
  - Input validation and sanitization
  - Activity logging and audit trails

## 📊 Database Evolution
The database schema evolves throughout the labs, demonstrating progressive complexity:

| Lab | Database | Key Tables | Features |
|-----|----------|------------|----------|
| **Lab 1** | `LibraryDB` | Books | Simple CRUD operations |
| **Lab 2** | `LibraryDB2` | Books, Authors, Genres | Normalized structure with relationships |
| **Lab 3** | `LibraryDB2` | Same as Lab 2 | Enhanced with validation |
| **Lab 4** | `LibraryDB4` | Books, Members, BookLoans | OOP-ready schema |
| **Lab 5** | `LibraryDB5` | Users, Books, BookLoans, Notifications | Full application schema |
| **Lab 6** | `LibraryDB5+` | Additional security tables | Production-ready with API support |

## 🛠️ Installation and Setup

### 📋 System Requirements
- **PHP**: 7.4 or higher (8.0+ recommended)
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+
- **Web Server**: Apache 2.4+ / Nginx 1.18+ / PHP Built-in Server
- **PHP Extensions**:
  - PDO (for database operations)
  - GD (for image processing)
  - FileInfo (for file type detection)
  - OpenSSL (for encryption)
  - cURL (for external API calls)
  - JSON (for API responses)

### 🚀 Quick Start Guide

#### Option 1: Using XAMPP/WAMP/MAMP (Recommended for Beginners)
1. **Download and Install**:
   - [XAMPP](https://www.apachefriends.org/) (Cross-platform)
   - [WAMP](https://www.wampserver.com/) (Windows)
   - [MAMP](https://www.mamp.info/) (macOS)

2. **Start Services**:
   - Start Apache and MySQL services
   - Verify PHP version: `http://localhost/dashboard/phpinfo.php`

3. **Clone Repository**:
   ```bash
   cd /path/to/htdocs  # or www folder
   git clone https://github.com/your-repo/phplabs.git
   # or download and extract ZIP file
   ```

#### Option 2: Using PHP Built-in Server (Quick Testing)
```bash
cd /path/to/phplabs
php -S localhost:8000
```

#### Option 3: Docker Setup (Advanced)
```bash
# Clone repository
git clone https://github.com/your-repo/phplabs.git
cd phplabs

# Start with Docker Compose
docker-compose up -d
```

### 🗄️ Database Setup

#### For Each Lab:
1. **Create Database**:
   ```sql
   -- Lab 1
   CREATE DATABASE LibraryDB;

   -- Lab 2
   CREATE DATABASE LibraryDB2;

   -- Lab 4
   CREATE DATABASE LibraryDB4;

   -- Lab 5
   CREATE DATABASE LibraryDB5;
   ```

2. **Import Sample Data** (if available):
   ```bash
   mysql -u root -p LibraryDB < Lab1/sample_data.sql
   ```

3. **Update Configuration**:
   - Edit `db_connect.php` or `db_config.php` in each lab
   - Update database credentials:
   ```php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'your_username');
   define('DB_PASSWORD', 'your_password');
   define('DB_NAME', 'LibraryDB');
   ```

### 🎯 Progressive Learning Path

| Step | Lab | Duration | Prerequisites |
|------|-----|----------|---------------|
| 1️⃣ | **Lab 1** | 2-3 hours | Basic HTML/CSS knowledge |
| 2️⃣ | **Lab 2** | 3-4 hours | Completion of Lab 1 |
| 3️⃣ | **Lab 3** | 4-5 hours | Understanding of forms |
| 4️⃣ | **Lab 4** | 5-6 hours | Basic programming concepts |
| 5️⃣ | **Lab 5** | 8-10 hours | OOP understanding |
| 6️⃣ | **Lab 6** | 4-6 hours | Web security awareness |

#### Detailed Learning Path:
1. **🔰 Start with Lab 1**: Master PHP and MySQL fundamentals
   - Understand CRUD operations
   - Learn form handling and basic security
   - Practice SQL queries and PHP syntax

2. **🎨 Progress to Lab 2**: Enhance UI and database design
   - Learn database normalization
   - Implement relationships and JOIN queries
   - Improve user interface with CSS

3. **🛡️ Continue to Lab 3**: Master validation and error handling
   - Implement robust form validation
   - Learn security best practices
   - Handle errors gracefully

4. **🏗️ Move to Lab 4**: Understand OOP principles
   - Refactor procedural code to OOP
   - Learn design patterns
   - Implement class hierarchies

5. **🚀 Advance to Lab 5**: Build complete web application
   - Implement authentication systems
   - Learn AJAX and responsive design
   - Integrate third-party services

6. **🔐 Complete Lab 6**: Production-ready features
   - Implement advanced security
   - Develop RESTful APIs
   - Learn deployment strategies

## 🔒 Security Features Evolution

### Lab-by-Lab Security Implementation:

| Lab | Security Features | Implementation Level |
|-----|------------------|---------------------|
| **Lab 1** | Basic input handling | Beginner |
| **Lab 2** | Improved form processing | Beginner+ |
| **Lab 3** | Input validation, XSS prevention | Intermediate |
| **Lab 4** | Encapsulation, data protection | Intermediate+ |
| **Lab 5** | Authentication, session management | Advanced |
| **Lab 6** | CSRF, JWT, vulnerability testing | Expert |

### Final Security Arsenal:
- ✅ **Password Security**: bcrypt hashing with salt
- ✅ **Session Management**: Secure session handling
- ✅ **CSRF Protection**: Token-based validation
- ✅ **XSS Prevention**: Output escaping and sanitization
- ✅ **SQL Injection**: Prepared statements throughout
- ✅ **Input Validation**: Client and server-side validation
- ✅ **Rate Limiting**: Brute force protection
- ✅ **Secure Headers**: HTTPS, HSTS, CSP implementation
- ✅ **JWT Authentication**: Stateless API security
- ✅ **Activity Logging**: Comprehensive audit trails

## 🌐 API Documentation (Lab 6)

### RESTful API Endpoints

#### Authentication
```http
POST /api/auth/login
Content-Type: application/json

{
  "username": "user@example.com",
  "password": "password123"
}

Response:
{
  "success": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 1,
    "username": "user@example.com",
    "role": "member"
  }
}
```

#### Books Management
```http
# Get all books
GET /api/books
Authorization: Bearer {token}

# Get specific book
GET /api/books/{id}
Authorization: Bearer {token}

# Create new book (Admin/Librarian only)
POST /api/books
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Book Title",
  "author": "Author Name",
  "isbn": "978-0123456789",
  "genre": "Fiction",
  "price": 19.99
}

# Update book (Admin/Librarian only)
PUT /api/books/{id}
Authorization: Bearer {token}

# Delete book (Admin only)
DELETE /api/books/{id}
Authorization: Bearer {token}
```

#### Loan Management
```http
# Borrow book
POST /api/loans
Authorization: Bearer {token}
Content-Type: application/json

{
  "book_id": 1,
  "due_date": "2024-02-15"
}

# Return book
PUT /api/loans/{id}/return
Authorization: Bearer {token}

# Get user's loans
GET /api/loans/user/{user_id}
Authorization: Bearer {token}
```

### API Response Format
```json
{
  "success": true|false,
  "data": {...},
  "message": "Success/Error message",
  "errors": [...],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "total_items": 100
  }
}
```

## 🚀 Deployment Guide

### Local Network Deployment (Lab 6)

#### Method 1: PHP Built-in Server
```bash
# Navigate to Lab5 directory
cd Lab5/Exercise1,2,3,4\(Final\)

# Start server accessible from network
php -S 0.0.0.0:8000

# Access from other devices
# http://YOUR_IP_ADDRESS:8000
```

#### Method 2: Apache Virtual Host
```apache
<VirtualHost *:80>
    ServerName library.local
    DocumentRoot "/path/to/Lab5/Exercise1,2,3,4(Final)"
    <Directory "/path/to/Lab5/Exercise1,2,3,4(Final)">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Database Configuration for Network Access
```sql
-- Create network user
CREATE USER 'libraryuser'@'%' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON LibraryDB5.* TO 'libraryuser'@'%';
FLUSH PRIVILEGES;
```

### Production Deployment Checklist
- [ ] **Environment Configuration**
  - [ ] Set `display_errors = Off` in php.ini
  - [ ] Configure proper error logging
  - [ ] Set secure session settings
  - [ ] Enable HTTPS with SSL certificate

- [ ] **Security Hardening**
  - [ ] Change default database passwords
  - [ ] Implement rate limiting
  - [ ] Configure firewall rules
  - [ ] Set proper file permissions (644 for files, 755 for directories)
  - [ ] Remove development files and comments

- [ ] **Performance Optimization**
  - [ ] Enable PHP OPcache
  - [ ] Configure database query caching
  - [ ] Implement CDN for static assets
  - [ ] Optimize images and CSS/JS files

## 🔧 Troubleshooting Guide

### Common Issues and Solutions

#### Database Connection Issues
```php
// Error: "Connection failed: Access denied"
// Solution: Check credentials in db_connect.php
define('DB_USERNAME', 'correct_username');
define('DB_PASSWORD', 'correct_password');

// Error: "Unknown database"
// Solution: Create database first
CREATE DATABASE LibraryDB;
```

#### Permission Errors
```bash
# Fix file permissions
chmod 644 *.php
chmod 755 uploads/
chmod 600 config.php

# Fix ownership (Linux/macOS)
chown -R www-data:www-data /path/to/project
```

#### Session Issues
```php
// Error: "Session not starting"
// Solution: Check session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();
```

#### AJAX Not Working
```javascript
// Common issue: Incorrect URL or missing CSRF token
$.ajaxSetup({
    beforeSend: function(xhr, settings) {
        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
            xhr.setRequestHeader("X-CSRFToken", $('[name=csrf_token]').val());
        }
    }
});
```

### Debug Mode Setup
```php
// Add to top of PHP files for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

## 🧪 Testing

### Manual Testing Checklist

#### Lab 1 Testing
- [ ] Database connection successful
- [ ] Create new book record
- [ ] Read/display all books
- [ ] Update existing book
- [ ] Delete book record
- [ ] Form validation working

#### Lab 2 Testing
- [ ] Author dropdown populated
- [ ] Genre dropdown populated
- [ ] Book-author relationship working
- [ ] Search functionality
- [ ] Filter by genre/author
- [ ] Responsive design on mobile

#### Lab 3 Testing
- [ ] Client-side validation
- [ ] Server-side validation
- [ ] Error messages display
- [ ] Form state persistence
- [ ] XSS prevention
- [ ] CSRF protection

#### Lab 4 Testing
- [ ] Book class instantiation
- [ ] E-book inheritance working
- [ ] Member class functionality
- [ ] Loan system operations
- [ ] Polymorphism demonstration
- [ ] Factory pattern implementation

#### Lab 5 Testing
- [ ] User registration
- [ ] Login/logout functionality
- [ ] Role-based access control
- [ ] AJAX book loading
- [ ] File upload for book covers
- [ ] Responsive dashboard
- [ ] Email notifications

#### Lab 6 Testing
- [ ] CSRF token validation
- [ ] API authentication
- [ ] JWT token generation
- [ ] Security vulnerability tests
- [ ] Rate limiting
- [ ] Network deployment

### Automated Testing (Optional)
```php
// Example PHPUnit test for Book class
class BookTest extends PHPUnit\Framework\TestCase
{
    public function testBookCreation()
    {
        $book = new Book("Test Title", "Test Author", 19.99);
        $this->assertEquals("Test Title", $book->getTitle());
        $this->assertEquals("Test Author", $book->getAuthor());
        $this->assertEquals(19.99, $book->getPrice());
    }

    public function testBookValidation()
    {
        $this->expectException(InvalidArgumentException::class);
        new Book("", "Author", -5.00); // Invalid title and price
    }
}
```

## 📚 Additional Resources

### Learning Materials
- **PHP Documentation**: [https://www.php.net/docs.php](https://www.php.net/docs.php)
- **MySQL Documentation**: [https://dev.mysql.com/doc/](https://dev.mysql.com/doc/)
- **Web Security**: [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- **RESTful APIs**: [REST API Tutorial](https://restfulapi.net/)

### Recommended Tools
- **IDE**: Visual Studio Code, PhpStorm, Sublime Text
- **Database**: phpMyAdmin, MySQL Workbench, Adminer
- **API Testing**: Postman, Insomnia, Thunder Client
- **Version Control**: Git, GitHub, GitLab

### Extensions and Libraries
```json
{
  "recommended_php_extensions": [
    "xdebug",
    "composer",
    "phpunit",
    "phpcs",
    "phpmd"
  ],
  "useful_libraries": [
    "monolog/monolog",
    "firebase/php-jwt",
    "phpmailer/phpmailer",
    "intervention/image"
  ]
}
```

## 🤝 Contributing

### How to Contribute
1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/new-feature`
3. **Make your changes** and test thoroughly
4. **Commit your changes**: `git commit -m "Add new feature"`
5. **Push to the branch**: `git push origin feature/new-feature`
6. **Submit a pull request**

### Contribution Guidelines
- Follow PSR-12 coding standards
- Add comments for complex logic
- Include tests for new features
- Update documentation as needed
- Ensure backward compatibility

### Bug Reports
When reporting bugs, please include:
- PHP version and environment details
- Steps to reproduce the issue
- Expected vs actual behavior
- Error messages and logs
- Screenshots if applicable

## 📄 License and Credits

### License
This project is released under the **MIT License** for educational purposes.

```
MIT License

Copyright (c) 2024 PHP Labs Project

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

### Credits and Acknowledgments
- **Educational Institution**: [Your Institution Name]
- **Course**: Web Development with PHP and MySQL
- **Instructor**: [Instructor Name]
- **Contributors**: [List of contributors]
- **Special Thanks**: PHP community, MySQL team, and open-source contributors

### Third-Party Libraries
- **Bootstrap**: CSS framework for responsive design
- **jQuery**: JavaScript library for DOM manipulation
- **Font Awesome**: Icon library
- **Google Fonts**: Typography enhancement

## 📞 Support and Contact

### Getting Help
- **Documentation**: Check individual lab README files
- **Issues**: Create an issue on GitHub
- **Discussions**: Use GitHub Discussions for questions
- **Email**: [your-email@example.com]

### Community
- **Discord**: [Your Discord Server]
- **Slack**: [Your Slack Workspace]
- **Forum**: [Your Forum Link]

---

## 🎉 Conclusion

This comprehensive lab series provides a complete journey from PHP basics to advanced web application development. By completing all six labs, you'll have built a production-ready Library Management System with modern security features, API capabilities, and deployment knowledge.

**Happy Coding! 🚀**

---

*Last Updated: January 2024*
*Version: 2.0*