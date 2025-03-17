<?php
use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);
// Fetch all groups and their expenses
$groups = $db->query("
    SELECT
        g.id AS group_id,
        g.name AS group_name,
        e.id AS expense_id,
        e.name AS expense_name,
        e.amount,
        e.date
    FROM groups AS g
    LEFT JOIN expenses AS e ON g.id = e.group_id
    ORDER BY g.id, e.date DESC
")->get();
// Fetch total expense
$totalExpense = $db->query("
    SELECT SUM(amount) AS total_expense FROM expenses
")->find();
// Fetch maximum expense
$maxExpense = $db->query("
    SELECT MAX(amount) AS amount,name FROM expenses WHERE amount = (SELECT MAX(amount) FROM expenses)
")->find();
// Fetch monthly expenses
$monthlyExpense = $db->query("
    SELECT
        DATE_FORMAT(date, '%Y-%m') AS month,
        SUM(amount) AS total_monthly_expense,
        MAX(amount) AS max_monthly_expense
        FROM expenses
        GROUP BY month
    ORDER BY month DESC
")->get();
view("index.view.php", [
    'heading' => 'Dashboard',
    'groups' => $groups,
    'totalExpense' => $totalExpense,
    'maxExpense' => $maxExpense,
    'monthlyExpense' => $monthlyExpense
]);
// view("index.view.php", [
//     'heading' => 'Dashboard',
// ]);
