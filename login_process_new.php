<?php
// Include the database connection file
require_once 'db_connect.php';
// Initialize the error message variable
$error = '';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists and the password is correct
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Check the user's job role and redirect accordingly
            switch ($user['job_role']) {
                case 'principal':
                    header('Location: principal_entry.php');
                    break;
                case 'admin':
                    header('Location: hostel_index.php');
                    break;
                case 'general':
                    header('Location: admin_index.php');
                    break;
                default:
                    // Invalid job role
                    $error = 'Invalid job role';
                    break;
            }
            exit();
        }
    }else{
    $error = 'Invalid username or password';
    }
}

// Close the database connection
$conn->close();
?>
