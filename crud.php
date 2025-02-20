<?php
$host = "localhost";
$user = "root"; // Change if your MySQL user is different
$pass = "";
$dbname = "crud_db";

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query('CREATE DATABASE IF NOT EXISTS $dbname');

// Select the database
$conn->select_db($dbname);

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Insert Record
if (isset($_POST["add"])) {
    $name = $_POST["name"];
    $stmt = $conn->prepare("INSERT INTO items (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

// Update Record
if (isset($_POST["update"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $stmt = $conn->prepare("UPDATE items SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

// Delete Record
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM items WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

// Fetch all records
$result = $conn->query("SELECT * FROM items");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Single Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 50%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-form {
            display: inline;
        }
    </style>
</head>

<body>
    <h2>Simple PHP CRUD</h2>

    <form method="POST">
        <input type="text" name="name" required placeholder="Enter Name">
        <button type="submit" name="add">Add</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td>
                    <form method="POST" class="edit-form">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="name" value="<?= $row['name'] ?>" required>
                        <button type="submit" name="update">Update</button>
                    </form>
                    <a href="index.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>