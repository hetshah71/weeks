<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

$group = $db->select('groups', ['*'], ['id' => $_GET['id']])[0] ?? null;

if (!$group) {
    abort(404);
}

view("groups/edit.view.php", [
    'heading' => 'Edit Group',
    'errors' => [],
    'group' => $group
]);
