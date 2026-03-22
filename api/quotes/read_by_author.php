<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote = new Quote($db);

  // Set author_id from GET param
  $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : die();

  // Quote read_by_author query
  $result = $quote->read_by_author();
  // Get row count
  $num = $result->rowCount();

  // Check if any quotes
  if ($num > 0) {
    // Quote array
    $quote_arr = array();
    $quote_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quote_item = array(
        'id' => $id,
        'quote' => $quote,
        'author_id' => $author_id,
        'category_id' => $category_id,
        'author' => $author,
        'category' => $category
      );

      // Push to "data"
      array_push($quote_arr['data'], $quote_item);
    }

    // Turn it to JSON & output
    echo json_encode($quote_arr);

  } else {
    // No quotes
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }