<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate quote object
  $quote = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check for all required quotes parameters
  if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  $quote->quote = $data->quote;
  $quote->author_id =$data->author_id;
  $quote->category_id = $data->category_id;

  // Check if author_id exists
  if (!$quote->author_exists()) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
    exit();
  }

  // Check if category_id exists
  if (!$quote->category_exists()) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
    exit();
  }

  // Create quote
  if($quote->create()) {
    echo json_encode(
      array(
        'id' => (int)$quote->id,
        'quote' => $quote->quote,
        'author_id' => (int)$quote->author_id,
        'category_id' => (int)$quote->category_id
      )
    );
  } else {
    echo json_encode(
      array('message' => 'Quote Not Created')
    );
  }