<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

// Kinda gross, yes? We'll refactor toward a cleaner approach in episode 33.
$note = $db->query('select * from notes where id = :id', [
    'id' => $_GET['id']
])->findOrFail();

authorize($note['user_id'] === $currentUserId);

//     $db->query('delete from notes where id = :id', [
//         'id' => $_GET['id']
//     ]);

//     header('location: /notes');
//     exit();
// } else {
//     $note = $db->query('select * from notes where id = :id', [
//         'id' => $_GET['id']
//     ])->findOrFail();

//     authorize($note['user_id'] === $currentUserId);

//     view("notes/show.view.php", [
//         'heading' => 'Note',
//         'note' => $note
//     ]);
// }

view("notes/show.view.php", [
    'heading' => 'Note',
    'note' => $note
]);