<?php

// 1. Create a Parent Class (File: Product.php)
class Product {
    // Define properties
    protected $product_name; // Protected allows access in this class and child classes
    protected $product_price; // Protected allows access in this class and child classes

    // Constructor to initialize properties
    public function __construct($name, $price) {
        $this->product_name = $name;
        $this->product_price = $price;
    }

    // Method to display product information
    public function displayProduct() {
        echo "Product Name: " . htmlspecialchars($this->product_name) . "<br>";
        echo "Product Price: $" . htmlspecialchars(number_format($this->product_price, 2)) . "<br>";
    }

    // Optional: Getter methods
    public function getProductName() {
        return $this->product_name;
    }

    public function getProductPrice() {
        return $this->product_price;
    }
}

?>
