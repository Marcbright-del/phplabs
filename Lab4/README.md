# Lab 4: Object-Oriented Programming Implementation

## Overview
Lab 4 transforms the procedural code from previous labs into an object-oriented architecture. This lab introduces classes, inheritance, encapsulation, and polymorphism to create a more maintainable, extensible, and robust library management system. The OOP approach provides better code organization, reusability, and separation of concerns.

## Files Structure

### Exercise 1: Basic OOP Implementation
- `Book.php`: Base Book class with properties and methods
- `create_book.php`: Book creation using OOP approach
- `view_books.php`: Book listing using Book objects
- `BookManager.php`: Class for handling book operations

### Exercise 2: Advanced OOP Concepts
- `AbstractBook.php`: Abstract base class for book types
- `PhysicalBook.php`: Class for physical books
- `Ebook.php`: Class for electronic books
- `BookFactory.php`: Factory class for creating book objects

### Exercise 3 and 4: Complete OOP System
- `Book.php`: Enhanced Book class with additional functionality
- `Ebook.php`: Electronic book class extending Book
- `Member.php`: Member class for library users
- `BookLoan.php`: Class representing book loans
- `library_test.php`: Main application file using all classes
- `dbconfig.php`: Database configuration
- `style.css`: Styling for the OOP-based application

## Class Hierarchy and Relationships

```
AbstractBook (abstract)
├── Book
│   └── Ebook
│
Member
│
BookLoan
│
BookManager
│
DatabaseHandler
```

## Key OOP Concepts Implemented

### 1. Classes and Objects
- Proper class definitions with properties and methods
- Object instantiation and lifecycle management
- Static methods and properties where appropriate
- Constructor and destructor methods

### 2. Encapsulation
- Private and protected properties with getter/setter methods
- Data validation within classes
- Information hiding and implementation details protection
- Proper access modifiers for all properties and methods

### 3. Inheritance
- Base and derived classes with proper inheritance relationships
- Method overriding with parent method calls where appropriate
- Use of protected members for derived class access
- Specialized behavior in child classes

### 4. Polymorphism
- Method overriding to provide specialized behavior
- Type hinting for method parameters
- Interface implementation (where applicable)
- Runtime behavior changes based on object type

### 5. Abstraction
- Abstract base classes with abstract methods
- Common functionality in base classes
- Specialized implementation in derived classes
- Separation of interface from implementation

### 6. Design Patterns
- Factory pattern for object creation
- Singleton pattern for database connection
- Repository pattern for data access
- MVC-like separation of concerns

## Database Schema
The database schema has been updated to support the OOP implementation:

```sql
CREATE DATABASE LibraryDB4;
USE LibraryDB4;

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

## Implementation Details

### Book Class
The `Book` class represents a physical book with properties and methods:

```php
class Book {
    protected $book_id;
    protected $title;
    protected $author;
    protected $price;
    protected $genre;
    protected $year;
    protected $is_borrowed;
    
    public function __construct($id, $title, $author, $price, $genre = null, $year = null, $is_borrowed = false) {
        $this->book_id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->genre = $genre;
        $this->year = $year;
        $this->is_borrowed = $is_borrowed;
    }
    
    // Getter and setter methods
    
    public function borrow() {
        if ($this->is_borrowed) {
            return false; // Already borrowed
        }
        $this->is_borrowed = true;
        return true;
    }
    
    public function returnBook() {
        if (!$this->is_borrowed) {
            return false; // Not borrowed
        }
        $this->is_borrowed = false;
        return true;
    }
    
    // Other methods...
}
```

### Ebook Class
The `Ebook` class extends the `Book` class with e-book specific functionality:

```php
class Ebook extends Book {
    private $format;
    private $download_link;
    private $discount_percentage = 20; // E-books are 20% cheaper
    
    public function __construct($id, $title, $author, $price, $genre = null, $year = null, $is_borrowed = false, $format = 'PDF') {
        parent::__construct($id, $title, $author, $price, $genre, $year, $is_borrowed);
        $this->format = $format;
    }
    
    public function getDiscountedPrice() {
        return $this->price * (1 - ($this->discount_percentage / 100));
    }
    
    // E-book specific methods...
}
```

### Member Class
The `Member` class represents a library member:

```php
class Member {
    private $member_id;
    private $name;
    private $email;
    private $join_date;
    private $status;
    private $borrowed_books = [];
    
    // Constructor, getters, setters
    
    public function borrowBook(Book $book) {
        if ($book->borrow()) {
            $this->borrowed_books[] = $book;
            return true;
        }
        return false;
    }
    
    public function returnBook(Book $book) {
        if ($book->returnBook()) {
            // Remove from borrowed books array
            return true;
        }
        return false;
    }
    
    // Other methods...
}
```

## How to Use

### Exercise 1: Basic OOP
1. Navigate to `Exercise1/view_books.php` to see the basic OOP implementation
2. Use `Exercise1/create_book.php` to add new books using the OOP approach

### Exercise 2: Advanced OOP
1. Explore the class hierarchy in the `Exercise2` directory
2. See how the Factory pattern is used to create different book types

### Exercise 3 and 4: Complete System
1. Navigate to `Exercise3and4/library_test.php` to access the full application
2. Use the dashboard to manage books, members, and loans
3. Explore the different views to see OOP in action

## Learning Objectives
- Understanding OOP principles and their application in PHP
- Creating class hierarchies with inheritance
- Implementing encapsulation for data protection
- Using polymorphism for flexible code
- Applying design patterns to solve common problems
- Refactoring procedural code to OOP
- Creating a maintainable and extensible codebase

## Next Steps
After completing Lab 4, proceed to Lab 5 which focuses on implementing a complete web application with authentication, authorization, and advanced features using the OOP foundation established in this lab.
