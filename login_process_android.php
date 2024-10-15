<?php
session_start(); 

include "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = $_POST['username'];
    $pass = $_POST['password'];
} else {
    $uname = $_GET['username'];
    $pass = $_GET['password'];
}

if (empty($uname)) {
    $response = array('success' => false, 'message' => 'User Name is required');
    echo json_encode($response);
    exit();
} else if (empty($pass)) {
    $response = array('success' => false, 'message' => 'Password is required');
    echo json_encode($response);
    exit();
} else {
    $sql = "SELECT * FROM users WHERE username='$uname'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
        if ($row['username'] === $uname && $row['password'] === $pass) {
            $_SESSION['username'] = $row['username'];
            $response = "Logged in!";
            echo json_encode($response);
            //header("Location: index.php");
            
            exit();
        } else {
            $response = array('success' => false, 'message' => 'Incorrect username or password');
            echo json_encode($response);
            exit();
        }
    } else {
        $response = array('success' => false, 'message' => 'No user found');
        echo json_encode($response);
        exit();
    }
}

mysqli_close($conn);
?>