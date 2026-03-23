<?php
  ini_set('display_errors', 0);
  error_reporting(0);

  header('Access-Control-Allow-Methods: PUT');
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

  // Check for required id parameter
  if (empty($data->id)) {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
    exit();
  }

  // Check for required quote parameter
  if (empty($data->quote)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  // Check for required author_id parameter
  if (empty($data->author_id)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  // Check for required category_id parameter
  if (empty($data->category_id)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  // Set properties
  $quote->id = $data->id;
  $quote->quote = $data->quote;
  $quote->author_id = $data->author_id;
  $quote->category_id = $data->category_id;

  // Check author_id exists in DB
  if (!$quote->author_exists()) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
    exit();
  }

  // Check category_id exists in DB
  if (!$quote->category_exists()) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
    exit();
  }

  // Update quote
  $result = $quote->update();
  if($result === true) {
    echo json_encode(
      array(
        'id' => (int)$quote->id,
        'quote' => $quote->quote,
        'author_id' => (int)$quote->author_id,
        'category_id' => (int)$quote->category_id
      )
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  } else {
    echo json_encode(
      array('message' => 'Quote Not Updated')
    );
  }