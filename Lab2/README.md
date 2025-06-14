# Lab 2: Improved UI and Database Relationships

## Overview
Lab 2 builds upon the foundation established in Lab 1 by enhancing the user interface and introducing database relationships. This lab focuses on creating a more structured and visually appealing application while implementing proper relationships between books, authors, and genres.

## Files Structure

### Exercise 1: Basic Improvements
- `index.php`: Enhanced landing page with improved navigation
- `styles.css`: Comprehensive CSS styling for better user experience

### Exercise 2: Database Relationships
- `add_book.php`: Improved book addition form with author selection
- `view_books.php`: Enhanced book listing with filtering options
- `db_config.php`: Updated database configuration
- `functions.php`: Reusable functions for common operations
- `styles.css`: CSS styling specific to Exercise 2

## Database Schema
Lab 2 introduces a more normalized database structure:

```sql
CREATE DATABASE LibraryDB2;
USE LibraryDB2;

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

-- Insert sample data for Authors
INSERT INTO Authors (author_name, birth_year, nationality) VALUES
('F. Scott Fitzgerald', 1896, 'American'),
('Harper Lee', 1926, 'American'),
('George Orwell', 1903, 'British'),
('Jane Austen', 1775, 'British');

-- Insert sample data for Genres
INSERT INTO Genres (genre_name) VALUES
('Classic'),
('Fiction'),
('Dystopian'),
('Romance'),
('Science Fiction');

-- Insert sample data for Books
INSERT INTO Books (book_title, author_id, publication_year, genre_id, price) VALUES
('The Great Gatsby', 1, 1925, 1, 12.99),
('To Kill a Mockingbird', 2, 1960, 2, 14.50),
('1984', 3, 1949, 3, 11.75),
('Pride and Prejudice', 4, 1813, 4, 9.99);
```

## Configuration
Update the database connection details in `db_config.php`:

```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'LibraryDB2');
```

## Features Implemented

### 1. Enhanced User Interface
- Responsive design with CSS styling
- Consistent header and footer across pages
- Improved navigation between different sections
- Form styling for better user experience
- Status messages with appropriate styling

### 2. Database Relationships
- Normalized database structure with separate tables for Authors and Genres
- Foreign key relationships between Books, Authors, and Genres
- Dropdown menus for selecting authors and genres when adding books
- Proper JOIN queries to display related information

### 3. Improved Book Management
- Enhanced book listing with author and genre information
- Filtering options by author, genre, or publication year
- Improved form validation with better error messages
- Option to add new authors if not in the database

### 4. Code Organization
- Separation of database configuration
- Reusable functions in a dedicated file
- Better organized HTML structure
- Improved PHP code organization with functions

## How to Use

1. **Setup Environment**:
   - Ensure your web server environment is running (XAMPP, WAMP, MAMP, etc.)
   - Start Apache and MySQL services

2. **Database Setup**:
   - Create the database and tables using the SQL commands provided above
   - Update connection details in `db_config.php`

3. **Access the Application**:
   - Place all files in your web server's document root
   - Navigate to `http://localhost/Lab2/Exercise2/view_books.php` to start

4. **Managing Books**:
   - View all books with filtering options
   - Add new books with author and genre selection
   - Edit existing books with improved form
   - Delete books with confirmation

## Exercise 1 vs Exercise 2
- **Exercise 1** focuses primarily on UI improvements while maintaining the original database structure
- **Exercise 2** implements both UI improvements and database relationships

## Learning Objectives
- Creating normalized database structures with relationships
- Implementing foreign key constraints
- Writing JOIN queries to retrieve related data
- Creating dropdown menus populated from database tables
- Improving UI with CSS styling
- Implementing filtering and search functionality
- Organizing code for better maintainability

## Advanced Features
- Dynamic filtering of books by multiple criteria
- Cascading dropdowns (genre selection based on author)
- Form validation with specific error messages
- Pagination for book listings (when the database grows)

## Limitations
- Limited error handling for database operations
- No user authentication or session management
- Basic security measures (addressed in later labs)

## Next Steps
After completing Lab 2, proceed to Lab 3 which focuses on form validation and error handling to create a more robust application.