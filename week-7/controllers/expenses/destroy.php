<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

try {
    // Check if expense exists
    $expense = $db->select('expenses', ['*'], ['id' => $_POST['id']])[0] ?? null;

    if (!$expense) {
        http_response_code(404);
        echo json_encode(['error' => 'Expense not found']);
        exit();
    }

    // Delete the expense
    $result = $db->delete('expenses', $_POST['id']);

    if ($result) {
        echo json_encode(['message' => 'Expense deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete expense']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while deleting the expense']);
}
exit();
