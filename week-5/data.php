<?php
// include("./form.html");
// if (isset($_POST["name"])) {
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $gender = $_POST['gender'];
//     $city = $_POST['city'];
//     $skills = implode(',', $_POST['skills']);
//     $details = $_POST['details'];
//     if (isset($_FILES['file'])) {
//         $filename = $_FILES['file']['name'];
//         $filepath = "./file/" . $filename;
//         if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
//             echo "File is uploaded succesfully";
//             echo "<br/>";
//         } else {
//             echo 'unable to upload file';
//         }
//     }
// }

// if (isset($_POST['name'])) {
//     include("./config.php");
//     $student = $conn->prepare("INSERT INTO `students`(`Id`, `name`, `email`, `password`, `gender`, `city`, `skills` ,`details`, `cv`) VALUES (NULL,'$name','$email','$password','$gender','$city','$skills','$details','$filepath')");
//     $student->execute();
//     if ($student) {
//         echo "data is inserted";
//     } else {
//         echo "operation is failed";
//     }
//     header("Location: data.php");
//     exit();
// }


session_start(); // Start session to store messages
include("./form.html");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("./config.php");

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $skills = isset($_POST['skills']) ? implode(',', $_POST['skills']) : '';
    $details = $_POST['details'];
    $filepath = '';

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $filename = $_FILES['file']['name'];
        $filepath = "./file/" . $filename;
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            $_SESSION['message'] = 'File upload failed!';
            header("Location: data.php");
            exit();
        }
    }

    // Insert into database
    try {
        $student = $conn->prepare("INSERT INTO `students`(`name`, `email`, `password`, `gender`, `city`, `skills`, `details`, `cv`) VALUES (?,?,?,?,?,?,?,?)");
        $success = $student->execute([$name, $email, $password, $gender, $city, $skills, $details, $filepath]);

        if ($success) {
            $_SESSION['message'] = "Data inserted successfully!";
        } else {
            $_SESSION['message'] = "Operation failed!";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }

    header("Location: data.php");
    exit();
}

// Display message
if (isset($_SESSION['message'])) {
    echo "<p style='color: green; font-weight: bold;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Remove message after displaying
}
?>
