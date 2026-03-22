<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate author object
  $category = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Check for required id parameter
  if (empty($data->id)) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
    exit();
  }

  // Set ID to delete
  $category->id = $data->id;

  // Delete author
  $result = $category->delete();
  if($result === true) {
    echo json_encode(
      array('message' => 'Category Deleted', 'id' => $category->id)
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
  } else {
    echo json_encode(
      array('message' => 'Category Not Deleted')
    );
  }