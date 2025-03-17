<?php

use Core\Database;
use Core\Validator;

$config = require base_path('config.php');
$db = new Database($config['database']);
echo json_encode([
    "uu"
]);
// Validate the form
$errors = [];

if (!isset($_POST['name']) || !Validator::string($_POST['name'], 1, 1000)) {
    $errors['name'] = 'A valid group name is required';
}

// Check if group already exists
if (empty($errors)) {
    $existingGroup = $db->select('groups', ['*'], ['name' => $_POST['name']]);
    if (!empty($existingGroup)) {
        http_response_code(422);
        echo json_encode(['error' => 'Group name "' . htmlspecialchars($_POST['name']) . '" already exists!']);
        // exit();
    }
}

// If there are validation errors, return them as JSON
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['error' => $errors['name']]);
    // exit();
}

try {
    // Store the group in the database
    $result = $db->insert('groups', [
        'name' => $_POST['name']
    ]);

    if ($result) {
        http_response_code(200);
        echo json_encode(['message' => 'Group created successfully']);
        // exit();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create group']);
        // exit();
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while creating the group']);
    // exit();
}
