<?php

  ini_set('display_errors', 0);
  error_reporting(0);

  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $author = new Author($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check for required author_id parameter
  if (empty($data->id)) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
    exit();
  }

  // Check for required author parameter
  if (empty($data->author)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  // Set ID and author to update
  $author->id = $data->id;
  $author->author = $data->author;

  // Update author
  $result = $author->update();
  if($result === true) {
    echo json_encode(
      array(
        'id' => (int)$author_id,
        'author' => $author->author
      )
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  }  else {
    echo json_encode(
      array('message' => 'Author Not Updated')
    );
  }