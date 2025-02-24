<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

// Get all groups for the dropdown
$groups = $db->select('groups');

// Get the expense with error handling
$expense = $db->select('expenses', ['*'], ['id' => $_GET['id']])[0] ?? null;

if (!$expense) {
    abort(404);
}

view("expenses/edit.view.php", [
    'heading' => 'Edit Expense',
    'errors' => [],
    'groups' => $groups,
    'expense' => $expense
]);
