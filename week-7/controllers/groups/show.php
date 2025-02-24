<?php

use Core\Database;

$config = require base_path('config.php');
$db = new Database($config['database']);

$groups = $db->select('groups');
// dd($groups);
// $groups = $db->query("SELECT * FROM groups")->get();
view("groups/show.view.php", [
    'heading' => 'Groups',
    'groups' => $groups,
]);

// dd($groups);
