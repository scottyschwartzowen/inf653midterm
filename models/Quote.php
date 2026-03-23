<?php

  class Quote {
    // DB Stuff
    private $conn;
    private $table = 'quotes';

    // Quote properties
    public $id;
    public $category_id;
    public $quote;
    public $author_id;
    public $category;
    public $author;
    
    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // GET Quotes
    public function read() {
    $query = 'SELECT
                q.id,
                q.quote,
                a.id AS author_id,
                a.author AS author,
                c.id AS category_id,
                c.category AS category
              FROM
                ' . $this->table . ' q
              LEFT JOIN authors a ON q.author_id = a.id
              LEFT JOIN categories c ON q.category_id = c.id
              ORDER BY q.id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      //Execute statement
      $stmt->execute();

      return $stmt;
    }

    // Get Single Quote
    public function read_single() {
      // Create query
      $query = 'SELECT
            q.id,
            q.quote,
            a.id AS author_id,
            c.id AS category_id,
            a.author AS author,
            c.category AS category
          FROM ' . $this->table . ' q 
          LEFT JOIN authors a ON q.author_id = a.id 
          LEFT JOIN categories c ON q.category_id = c.id
          WHERE
          q.id = :id
          LIMIT 1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID with named placeholder
      $stmt->bindParam(':id', $this->id);

      // Execute query
      $stmt->execute();

      // Fetch associative array
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // Set properties if row exists
      if ($row) {
        // Set properties
        $this->id = $row['id'];
        $this->quote = $row['quote'];
        $this->author_id = $row['author_id'];
        $this->category_id = $row['category_id'];
        $this->author = $row['author'];
        $this->category = $row['category'];
        return true;
      }
      return false;
    }

    // Get Single Quote by author_id
    public function read_by_author() {
      // Create query
      $query = 'SELECT
            q.id,
            q.quote,
            a.id AS author_id,
            c.id AS category_id,
            a.author AS author,
            c.category AS category
          FROM
            ' . $this->table . ' q
          LEFT JOIN authors a ON q.author_id = a.id
          LEFT JOIN categories c ON q.category_id = c.id
          WHERE
            q.author_id = :author_id
          ORDER BY q.id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind Param
      $stmt->bindParam(':author_id', $this->author_id);

      // Execute
      $stmt->execute();

      return $stmt;
    }

    // Get Single Quote by category_id
    public function read_by_category() {
      // Create query
      $query = 'SELECT
            q.id,
            q.quote,
            a.id AS author_id,
            c.id AS category_id,
            a.author AS author,
            c.category AS category
          FROM
            ' . $this->table . ' q
          LEFT JOIN authors a ON q.author_id = a.id
          LEFT JOIN categories c ON q.category_id = c.id
          WHERE
            q.category_id = :category_id
          ORDER BY q.id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind Param
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute
      $stmt->execute();

      return $stmt;
    }
    
    // Get Single Quote by author_id and category_id
    public function read_by_author_and_category() {
      // Create query
      $query = 'SELECT
            q.id,
            q.quote,
            a.id AS author_id,
            c.id AS category_id,
            a.author AS author,
            c.category AS category
          FROM
            ' . $this->table . ' q
          LEFT JOIN authors a ON q.author_id = a.id
          LEFT JOIN categories c ON q.category_id = c.id
          WHERE
            q.author_id = :author_id
          AND
            q.category_id = :category_id
          ORDER BY q.id ASC';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind Param
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute
      $stmt->execute();

      return $stmt;
    }

    // Check if author_id exists before inserting
    public function author_exists() {
      $query = 'SELECT id FROM authors WHERE id = :author_id LIMIT 1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Bind parameters
      $stmt->bindParam(':author_id', $this->author_id);
      // Execute query
      $stmt->execute();

      return $stmt->rowCount() > 0;
    }

    // Check if category_id exists before inserting
    public function category_exists() {
      $query = 'SELECT id FROM categories WHERE id = :category_id LIMIT 1';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Bind parameters
      $stmt->bindParam(':category_id', $this->category_id);
      // Execute query
      $stmt->execute();

      return $stmt->rowCount() > 0;
    }

    // Create Quote
    public function create() {
      // Create query
      $query = 'INSERT INTO ' . $this->table . '
          (quote, author_id, category_id)
        VALUES
          (:quote, :author_id, :category_id)';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));

      // Bind name parameters
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }

    // Update Quote
    public function update() {
      // Create query w/ named parameter
      $query = 'UPDATE ' . $this->table . '
              SET quote = :quote, author_id = :author_id, category_id = :category_id
              WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->id = htmlspecialchars(strip_tags($this->id));
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));

      // Bind name parameters
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      // Execute query
      if ($stmt->execute()) {
        if ($stmt->rowCount() >0) {
          return true;
        }
        return null;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->errorInfo()[2]);
      return false;
    }

    // Delete quote
    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Sanitize data
      $this->id = htmlspecialchars(strip_tags($this->id));

      // Bind parameter
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