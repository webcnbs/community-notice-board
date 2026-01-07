<?php
// models/Category.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the Category model class
class Category {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    /**
     * Retrieve all categories
     * 
     * @return array - List of all categories ordered alphabetically by name
     */
    public function all() {
        // Execute query to select all categories ordered by name
        return $this->pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    /**
     * Create a new category
     * 
     * @param string $name - Name of the category
     * @param string $description - Description of the category
     * @param string $color - Color code associated with the category
     */
    public function create(string $name, string $description, string $color) {
        // Prepare SQL statement to insert a new category
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, description, color_code) VALUES (?, ?, ?)");
        // Execute the statement with provided values
        $stmt->execute([$name, $description, $color]);
    }

    /**
     * Delete a category by its ID
     * 
     * @param int $id - ID of the category to delete
     */
    public function delete(int $id) {
        // Prepare SQL statement to delete a category by ID
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE category_id=?");
        // Execute the statement with the category ID
        $stmt->execute([$id]);
    }
}