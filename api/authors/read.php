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

  // Author query
  $result = $author->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any authors
  if ($num > 0) {
    // Author array
    $authors_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $author_item = array(
        'id' => $id,
        'author' => $author
      );

      // Push to array
      array_push($authors_arr, $author_item);
    }

    // Turn it to JSON & output plain array
    echo json_encode($authors_arr);

  } else {
    // No authors
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  }