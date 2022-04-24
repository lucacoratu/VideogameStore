<?php
    //Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gamestore";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    session_start();
    //Check if the username or email inserted exists in the database
    $usernameOrEmail = $_POST['username'];
    $usernameOrEmailEscaped = mysqli_real_escape_string($conn, $usernameOrEmail);
    
    $sql = "select ID from accounts where Username = '$usernameOrEmailEscaped' or Email ='$usernameOrEmailEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0) {
        $_SESSION['message'] = "The username/email that u inserted does not exist!";
        header('Location: forgotpassword.php');
        die("The username/email that u inserted does not exist!");
    }

    //TO DO check if the password and new password match
    //Update the credentials for the requested account
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    if($password != $confirmPassword){
        $_SESSION['message'] = 'Passwords do not match';
        die("Password and confirm password have to match");
    }

    //Hash the password and update the account credentials
    $hashedPassword = hash(hash_algos()[13], $password);

    $sql = "update accounts set Password = '$hashedPassword' where Username = '$usernameOrEmailEscaped' or Email ='$usernameOrEmailEscaped'";
    $result = $conn->query($sql);
    if($result == false){
        $_SESSION['message'] = 'Cannot change the account credentials';
        header('Location: forgotpassword.php');
        die();
    }

    $_SESSION['message'] = 'Account credentials have been updated!';
    header('Location: forgotpassword.php');
?>