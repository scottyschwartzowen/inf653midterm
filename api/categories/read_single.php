<?php

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  // include database & model
  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category object
  $category = new Category($db);

  // Get ID
  $category->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get category
  if ($category->read_single()) {
    // Create array
    $cat_arr = array(
      'id' => $category->id,
      'category' => $category->category
    );

    // Convert to JSON
    echo json_encode($cat_arr);

  } else {
    // No categories
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
}