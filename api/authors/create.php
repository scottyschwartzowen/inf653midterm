<?php

  ini_set('display_errors', 0);
  error_reporting(0);

  header('Access-Control-Allow-Methods: POST');
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

  // Check for required author parameter
  if (empty($data->author)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  $author->author = $data->author;

  // Create author
  if($author->create()) {
    echo json_encode(
      array(
        'id' => (int)$author->id,
        'author' => $author->author
      )
    );
  } else {
    echo json_encode(
      array('message' => 'Author Not Created')
    );
  }