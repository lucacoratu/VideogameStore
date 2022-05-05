<?php
    //Change the account type to the value received as post
    //Check if the user is logged in 
    session_start();
    if(isset($_SESSION['username'])){
        if($_SESSION['acc_type'] == 2){
            //Get the values received from post supervariable
            $usernameModify = $_POST['modify'];
            $accType = $_POST['modifyType'];
            
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
             

            $sql = "update accounts set AccTypeID = $accType where username = '$usernameModify'";
            $result = $conn->query($sql);

            if($result == false){
                die("Insertion failed!");
            }

            exit("Account type has changed for user: $usernameModify");
        }
        else{
            die("Cannot modify accounts when you are not admin!");
        }
    }
    else{
        die("Cannot modify accounts when you are not connected!");
    }
?>