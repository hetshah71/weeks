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

// If there are validation errors, return them as JSON
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['errors' => $errors]);
    exit();
}

try {
    // Store the expense in the database
    $result = $db->insert('expenses', [
        'name' => $_POST['name'],
        'amount' => $_POST['amount'],
        'date' => $_POST['date'],
        'group_id' => $_POST['group_id']
    ]);

    if (!$result) {
        echo json_encode(['message' => 'Expense created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create expense']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while creating the expense']);
}
exit();
