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

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
            // Check the user's job role and send the appropriate response
            switch ($user['job_role']) {
                case 'principal':
                    $response['success'] = true;
                    $response['message'] = 'Login successful';
                    $response['role'] = 'principal';
                    break;
                case 'admin':
                    $response['success'] = true;
                    $response['message'] = 'Login successful';
                    $response['role'] = 'admin';
                    break;
                case 'general':
                    $response['success'] = true;
                    $response['message'] = 'Login successful';
                    $response['role'] = 'general';
                    break;
                default:
                    $response['success'] = false;
                    $response['message'] = 'Invalid job role';
                    break;
            }
        
    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid username or password';
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // Ensure that no additional output is included
}
?>
