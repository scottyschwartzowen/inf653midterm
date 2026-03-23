<?php
  class Author {
    // DB Stuff
    private $conn;
    private $table = 'authors';

    // Author Properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // GET Authors
    public function read() {
      // Create query
      $query = 'SELECT 
            a.id,
            a.author
          FROM
            ' . $this->table . ' a';

      // Prepared statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single Author
    public function read_single() {
      // Create query
      $query = 'SELECT 
      a.id,
      a.author
      FROM
        ' . $this->table . ' a
      WHERE
        a.id = ?
      LIMIT 1 OFFSET 0';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      // Fetch associative array
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Check if row exists
      if ($row) {
        // Set properties
      $this->id = $row['id'];
      $this->author = $row['author'];
      return true;
      }
      return false;
    }

    // Create Author
    public function create() {

      // Create query
      $query = 'INSERT INTO ' . $this->table . ' (author)
        VALUES (:author)';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->author = htmlspecialchars(strip_tags($this->author));

      // Binding named parameters
      $stmt->bindParam(':author', $this->author);

      // Execute query
      if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
      }
      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }

    // Update Author
    public function update(): bool | null {

      // Create query w/ named parameter
      $query = 'UPDATE ' . $this->table . '
        SET author = :author
        WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->id = htmlspecialchars(strip_tags($this->id));
      $this->author = htmlspecialchars(strip_tags($this->author));

      // Binding named parameters
      $stmt->bindParam(':author', $this->author);
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

    // Delete Author
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