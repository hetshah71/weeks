<?php

use Core\Database;


$config = require base_path('config.php');
$db = new Database($config['database']);

$group = $db->select('groups', ['*'], ['id' => $_POST['id']]);
// $db->query('delete from groups where id = :id', [
//     'id' => $_POST['id']
// ]);

$db->delete('groups', $_POST['id']);
// $group = $db->query('select * from groups')->get();

header('location: /groups');
exit();
