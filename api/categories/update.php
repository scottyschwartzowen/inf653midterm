<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  header('Access-Control-Allow-Methods: PUT');
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

  // Check for required category_id parameter
  if (empty($data->id)) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
    exit();
  }

  // Check for required category parameter
  if (empty($data->category)) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    exit();
  }

  // Set ID to update
  $category->id = $data->id;
  $category->category = $data->category;

  // Update category
  $result = $category->update();
  if($result === true) {
    echo json_encode(
      array('message' => 'Category Updated')
    );
  } elseif ($result === null) {
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
  } else {
    echo json_encode(
      array('message' => 'Category Not Updated')
    );
  }