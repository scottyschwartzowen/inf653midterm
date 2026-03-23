<?php

  ini_set('display_errors', 0);
  error_reporting(0);

  header('Access-Control-Allow-Methods: DELETE');
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

  // Check for required id parameter
  if (empty($data->id)) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
    exit(); 
  }

  // Set ID to delete
  $author->id = $data->id;

  // Delete author
  $result = $author->delete();
  if($result === true) {
    echo json_encode(
      array('id' => (int)$author->id)
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'author_id Not Found')
    );
  } else {
    echo json_encode(
      array('message' => 'Author Not Deleted')
    );
  }