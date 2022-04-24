<?php
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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //Take the username and the password
        $username = $_POST['username'];
        $password = $_POST['password'];

        //Hash the password

        $hashedPassword = hash(hash_algos()[13], $password);

        $sql = "select Username,AccTypeID from accounts where Username='$username' and Password='$hashedPassword'";

        $result = $conn->query($sql);
        
        if($result->num_rows == 0){
            $alertMessage = "Invalid credentials";
            //echo "<script>alert(\"$alertMessage\")</script>";
            header('Location: login.html',false);
        }
        else{
            //Successfully logged in

            //Create the session
            session_start();

            $row = $result->fetch_assoc();
            $_SESSION['username'] = $username;
            $_SESSION['acc_type'] = $row['AccTypeID'];

            header('Location: index.php');
        }
    }
?>