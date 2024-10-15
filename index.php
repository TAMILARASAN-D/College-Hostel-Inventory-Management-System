<?php
session_start();
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
        $_SESSION['username'] = $username;
        // Check the user's job role and redirect accordingly
        switch ($user['job_role']) {
            case 'principal':
                header('Location: principalEntryPage.php');
                exit();
            case 'admin':
                header('Location: adminEntryPage.php');
                exit();
            case 'general':
                header('Location: viewOnlyReportPage.php');
                exit();
            default:
                $error = 'Invalid job role';
                break;
        }
    } else {
        $error = 'Invalid username or password';
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>TPGIT Hostel Inventory Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="header">
        <div class="logo-left">
            <img src="tn1.png" alt="Left Logo">
        </div>
        <h1>THANTHAI PERIYAR GOVERNMENT INSTITUTE OF TECHNOLOGY <br>HOSTEL INVENTORY MANAGEMENT SYSTEM</h1>
        <div class="logo-right">
            <img src="01_tpgit logo_Final.png" alt="Right Logo">
        </div>
    </div>
    <div class="item">
        <h1>LOGIN</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form id="login-form" method="post" action="">
            <br>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>
            <br>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
