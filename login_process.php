<?php
session_start(); 

include "db_connect.php";
/*
if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    //$uname = validate($_POST['username']);

    //$pass = validate($_POST['password']);*/
    $uname = $_POST['username'];

    $pass = $_POST['password'];

    if (empty($uname)) {

        header("Location: login.php?error=User Name is required");

        exit();

    }else if(empty($pass)){

        header("Location: login.php?error=Password is required");

        exit();

    }else{

        $sql = "SELECT * FROM users WHERE username='$uname'";
        
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0) {
            if ($row['username'] === $uname && $row['password'] === $pass) {

                $_SESSION['username'] = $row['username'];

                header("Location: hostel_index.php");

                exit();

            }
            else{

                header("Location: login.php?error=Incorect User name or password");

                exit();

            }

        }
        else{
            header("Location: login.php?error=No user found");

                exit();
        }
    }

    mysqli_close($conn);
?>

