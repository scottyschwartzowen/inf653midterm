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
  $quote_obj = new Quote($db);

  // Quote read query
  $result = $quote_obj->read();
  // Get row count
  $num = $result->rowCount();

  // Check if any quotes
  if ($num > 0) {
    // Quote array
    $quote_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quote_item = array(
      'id' => $id,
      'quote' => $quote,
      'author' => $author,
      'category' => $category
    );

      // Push to "data"
      array_push($quote_arr, $quote_item);
    }

    // Turn it to JSON & output
    echo json_encode($quote_arr);

  } else {
    // No quotes
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }