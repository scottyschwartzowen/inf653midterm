<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  $method = $_SERVER['REQUEST_METHOD'];

  if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
  }

  // Conditional logic routing to CRUD operations
  if ($method === 'GET') {
    if (isset($_GET['id'])) {
        require 'read_single.php';
    } else {
        require 'read.php';
    }
    } elseif ($method === 'POST') {
        require 'create.php';
    } elseif ($method === 'PUT') {
        require 'update.php';
    } elseif ($method === 'DELETE') {
        require 'delete.php';
    } else {
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
    }