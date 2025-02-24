<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

$groups = $db->select('groups');
// $groups = $db->query("SELECT * FROM groups")->get();

view("expenses/create.view.php", [
    'heading' => 'Expenses',
    'groups' => $groups,
]);
// header('location:/expenses');
// die();