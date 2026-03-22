<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Get ID
  $author->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get author
  if ($author->read_single()) {
    // Create array
    $author_arr = array(
      'id' => $author->id,
      'author' => $author->author
    );

    // Convert to JSON
    echo json_encode($author_arr);

  } else {
    // No authors found
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  }