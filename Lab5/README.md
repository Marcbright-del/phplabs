# Lab 5: Complete Web Application with Authentication and AJAX

## Overview
Lab 5 builds upon the object-oriented foundation established in Lab 4 to create a complete, professional-grade library management system. This lab introduces user authentication, role-based access control, AJAX for dynamic content loading, and a modern responsive interface. The application follows MVC-like architecture patterns for better organization and maintainability.

## Files Structure

### Core Application Files
- `index.php`: Entry point and login page
- `dashboard.php`: Main application dashboard after login
- `logout.php`: User logout handler
- `register.php`: New user registration

### Includes Directory
- `includes/db_connect.php`: Database connection with PDO
- `includes/functions.php`: Shared utility functions
- `includes/auth.php`: Authentication functions
- `includes/header.php`: Common header template
- `includes/footer.php`: Common footer template
- `includes/sidebar.php`: Navigation sidebar template

### Views Directory
- `views/dashboard_home_content.php`: Dashboard home view
- `views/available_books_content.php`: Available books listing
- `views/my_borrowed_books_content.php`: User's borrowed books
- `views/manage_all_books_content.php`: Admin book management
- `views/add_book_form_content.php`: Book addition form
- `views/edit_book_form_content.php`: Book editing form
- `views/view_users_content.php`: User management (admin only)
- `views/profile_content.php`: User profile management

### Classes Directory
- `classes/User.php`: User class with authentication methods
- `classes/Book.php`: Enhanced Book class from Lab 4
- `classes/Ebook.php`: Electronic book class
- `classes/Member.php`: Library member class
- `classes/BookLoan.php`: Book loan management class
- `classes/Database.php`: PDO database wrapper class

### Assets Directory
- `css/style.css`: Main stylesheet
- `css/responsive.css`: Responsive design rules
- `js/main.js`: Core JavaScript functionality
- `js/ajax.js`: AJAX request handling
- `js/validation.js`: Client-side form validation
- `images/`: Directory for application images

## Database Schema
The database schema has been expanded to include users and roles:

```sql
CREATE DATABASE LibraryDB5;
USE LibraryDB5;

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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE ActivityLog (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
```

## Key Features Implemented

### 1. User Authentication System
- Secure password hashing with bcrypt
- Session-based authentication
- Remember me functionality with secure cookies
- Password reset via email
- Account lockout after failed login attempts
- User registration with email verification

### 2. Role-Based Access Control
- Three user roles: admin, librarian, and member
- Different dashboard views based on user role
- Permission-based access to features
- Admin panel for user management
- Role-specific navigation options

### 3. AJAX Implementation
- Dynamic content loading without page refresh
- Live search functionality for books
- Form submissions with AJAX
- Real-time validation feedback
- Pagination with AJAX loading

### 4. Responsive Design
- Mobile-first approach
- Flexbox and CSS Grid layout
- Responsive navigation with hamburger menu
- Optimized for various screen sizes
- Touch-friendly interface elements

### 5. Advanced Book Management
- Book cover image upload
- Rich text editor for book descriptions
- ISBN validation and auto-lookup
- Barcode generation and scanning
- Book availability status tracking
- E-book file upload and management

### 6. Member Features
- Personal reading history
- Book reservation system
- Due date reminders
- Favorite books list
- Reading recommendations
- Profile management

### 7. Security Features
- CSRF protection for all forms
- XSS prevention with output escaping
- SQL injection protection with prepared statements
- Input validation on both client and server
- Secure file upload handling
- Activity logging for audit trails

## Installation and Configuration

1. **Database Setup**:
   - Create the database using the schema provided above
   - Import sample data if needed (sample_data.sql)

2. **Configuration**:
   - Copy `config.sample.php` to `config.php`
   - Update database connection details
   - Configure email settings for password reset
   - Set application URL and paths

3. **Server Requirements**:
   - PHP 7.4 or higher
   - MySQL 5.7 or higher / MariaDB 10.3+
   - PDO PHP extension
   - GD PHP extension (for image handling)
   - FileInfo PHP extension
   - mod_rewrite enabled (for clean URLs)

4. **File Permissions**:
   - Set write permissions for uploads directory
   - Set write permissions for logs directory
   - Protect configuration files from public access

## User Guide

### Admin Functions
- Manage all books (add, edit, delete)
- Manage users (create, edit, suspend, delete)
- View all book loans and history
- Generate reports (usage, overdue, inventory)
- Configure system settings

### Librarian Functions
- Manage books (add, edit)
- Process book loans and returns
- Manage member accounts
- Send notifications to members
- Generate basic reports

### Member Functions
- Browse available books
- Borrow and return books
- View personal loan history
- Update profile information
- Save favorite books

## AJAX Implementation Details

### Book Search
```javascript
// Live search implementation
$('#search-book').on('keyup', function() {
    let query = $(this).val();
    if (query.length > 2) {
        $.ajax({
            url: 'ajax/search_books.php',
            method: 'POST',
            data: { query: query },
            dataType: 'json',
            success: function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(function(book) {
                        html += `<div class="book-item">
                                    <h3>${book.title}</h3>
                                    <p>By: ${book.author}</p>
                                    <p>Status: ${book.status}</p>
                                    <a href="view_book.php?id=${book.id}">View Details</a>
                                </div>`;
                    });
                } else {
                    html = '<p>No books found matching your search.</p>';
                }
                $('#search-results').html(html).show();
            }
        });
    } else {
        $('#search-results').hide();
    }
});
```

## Security Implementation

### CSRF Protection
```php
// Generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Usage in forms
echo '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';

// Validation in form processing
if (!validate_csrf_token($_POST['csrf_token'])) {
    die('CSRF token validation failed');
}
```

## Learning Objectives
- Implementing secure user authentication
- Creating role-based access control systems
- Using AJAX for dynamic content loading
- Building responsive web interfaces
- Applying advanced security practices
- Creating a complete web application
- Implementing MVC-like architecture patterns
- Using modern PHP development practices

## Next Steps
After completing Lab 5, proceed to Lab 6 which focuses on advanced security features, API development, and deployment considerations.