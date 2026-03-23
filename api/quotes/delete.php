<?php

  ini_set('display_errors', 0);
  error_reporting(0);

  header('Access-Control-Allow-Methods: DELETE');
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
      array('message' => 'quote_id Not Found')
    );
    exit();
  }

  // Set ID to delete
  $quote->id = $data->id;

  // Delete quote
  $result = $quote->delete();
  if($result === true) {
    echo json_encode(
      array('id' => (int)$quote->id)
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  } else {
    echo json_encode(
      array('message' => 'Quote Not Deleted')
    );
  }