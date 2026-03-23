<?php

  class Category {
    // DB Stuff
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // GET Categories
    public function read() {
      // Create query
      $query = 'SELECT 
            c.id,
            c.category
          FROM
            ' . $this->table . ' c';

      // Prepared statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Category
    public function read_single() {
      // Create query
      $query = 'SELECT 
            c.id,
            c.category
          FROM
            ' . $this->table . ' c
          WHERE
            c.id = ?
          LIMIT 1 OFFSET 0';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      // Fetch associative array
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties if row exists
      if ($row) {
        // Set properties
        $this->id = $row['id'];
        $this->category = $row['category'];
        return true;
      }
      return false;
    }

    // Create Category
    public function create() {

      // Create query
      $query = 'INSERT INTO ' . $this->table . ' (category)
        VALUES (:category)';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->category = htmlspecialchars(strip_tags($this->category));

      // Binding named parameters
      $stmt->bindParam(':category', $this->category);

      // Execute query
      if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
      }
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }

    // Update Category
    public function update() {

      // Create query w/ named parameter
      $query = 'UPDATE ' . $this->table . '
        SET category = :category
        WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->id = htmlspecialchars(strip_tags($this->id));
      $this->category = htmlspecialchars(strip_tags($this->category));

      // Binding named parameters
      $stmt->bindParam(':category', $this->category);
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return true;
        }
        return null;
      }
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }

    // Delete Category
    public function delete() {
      // Create query w/ named parameter
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind ID w/ named parameter
      $stmt->bindParam(':id', $this->id);

      // Execute query
      if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
          return true;
        }
        return null;
      }
      
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }
  }