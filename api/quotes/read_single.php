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

  // Get ID
  $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get quote if found
  if ($quote->read_single()) {
    // Create array (using object properties)
    $quote_arr = array(
      'id' => $quote->id,
      'quote' => $quote->quote,
      'author' => $quote->author,
      'category' => $quote->category
    );

    // Convert to JSON
    echo json_encode($quote_arr);

  } else {
    // No quote
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  };