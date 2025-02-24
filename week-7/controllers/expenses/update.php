<?php

use Core\Database;
use Core\Validator;

$config = require base_path('config.php');
$db = new Database($config['database']);

// Validate the form
$errors = [];

if (!isset($_POST['name']) || !Validator::string($_POST['name'], 1, 1000)) {
    $errors['name'] = 'A valid expense name is required';
}

if (!isset($_POST['amount']) || !is_numeric($_POST['amount']) || $_POST['amount'] <= 0) {
    $errors['amount'] = 'A valid amount greater than 0 is required';
}

if (!isset($_POST['date']) || !strtotime($_POST['date'])) {
    $errors['date'] = 'A valid date is required';
}

if (!isset($_POST['group_id']) || !is_numeric($_POST['group_id'])) {
    $errors['group_id'] = 'Please select a group';
}

// If there are validation errors, return them as JSON for AJAX handling
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['errors' => $errors]);
    exit;
}

// Get the ID from POST data
$id = $_POST['id'];

// Update the expense
try {
    $result = $db->update('expenses', [
        'name' => $_POST['name'],
        'amount' => $_POST['amount'],
        'date' => $_POST['date'],
        'group_id' => $_POST['group_id']
    ], $id);

    if ($result) {
        // Return success response for AJAX
        echo json_encode(['message' => 'Expense updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['errors' => ['database' => 'Failed to update expense']]);
    }
    exit;
    
} catch (Exception $e) {
    // Return error response for AJAX
    http_response_code(500);
    echo json_encode(['errors' => ['database' => 'Failed to update expense']]);
    exit;
}
