<?php

use Core\Database;
use Core\Validator;

$config = require base_path('config.php');
$db = new Database($config['database']);

// Validate the form
$errors = [];

if (!isset($_POST['name']) || !Validator::string($_POST['name'], 1, 1000)) {
    $errors['name'] = 'A valid group name is required';
}

// If there are validation errors, return them as JSON for AJAX handling
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['errors' => $errors]);
    exit;
}

// Get the ID from POST data
$id = $_POST['id'];

// Update the group
try {
    $result = $db->update('groups', [
        'name' => $_POST['name']
    ], $id);

    if ($result) {
        // Return success response for AJAX
        echo json_encode(['message' => 'Group updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['errors' => ['database' => 'Failed to update group']]);
    }
    exit;
    
} catch (Exception $e) {
    // Return error response for AJAX
    http_response_code(500);
    echo json_encode(['errors' => ['database' => 'Failed to update group']]);
    exit;
}
