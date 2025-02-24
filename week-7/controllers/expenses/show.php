<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);
$expenses = $db->query('SELECT e.id, e.name, e.amount, e.date, g.name AS category
                        FROM expenses e
                        JOIN groups g ON e.group_id = g.id
                        ORDER BY e.date DESC')->get();
view("expenses/show.view.php", [
    'heading' => 'expenses',
    'expenses' => $expenses,
]);
