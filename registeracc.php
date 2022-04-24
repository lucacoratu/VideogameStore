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
    //echo "Connected successfully";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $accusername = $_POST["username"];
        $accpassword = $_POST["password"];
        $lastname = $_POST["last-name"];
        $firstname = $_POST["first-name"];
        $email = $_POST["email"]; 

        $address = null;
        if(array_key_exists("address",$_POST) == true){
            $address = $_POST["address"]; 
        }
        $facebook = null;
        if(array_key_exists("facebook",$_POST) == true){
            $facebook = $_POST["facebook"];
        }
        $instagram = null;
        if(array_key_exists("instagram", $_POST) == true){
            $instagram = $_POST["instagram"];
        }

        //echo "$accusername\t$accpassword\t$lastname";

        //Check if the account does not exist
        $sql = "select Username from accounts where Username='$accusername'";
        $result = $conn->query($sql);

        if($result->num_rows == 0){
            //Insert the account into the database

            //Hash the password
            $hashedPassword = hash(hash_algos()[13], $accpassword);
            //print_r($hashedPassword);

            $address = ($address == null) ? "NULL" : $address; 
            $facebook = ($facebook == null) ? "NULL" : $facebook; 
            $instagram = ($instagram == null) ? "NULL" : $instagram; 
            $sql = "insert into accounts(Username, Password, Salt, Email, LastName, FirstName, DisplayName, Address, Facebook, Instagram, Funds, JoinDate, AccTypeID) values('$accusername', '$hashedPassword', 'ceva', '$email', '$lastname', '$firstname', 'nume', '$address', '$facebook', '$instagram', 0.0, current_timestamp, 1)";
            $result = $conn->query($sql);

            if($result == false)
                echo "Cannot insert in the database!\n";
            else{
                //Start the session as user is already logged in
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['acc_type'] = "Normal";
                
                //Get the id of the account
                $accId = $conn->insert_id;

                //Create a cart for the account
                $sql = "insert into shoppingcarts (AccId) values($accId)";
                $result = $conn->query($sql);

                if($result == false){
                    die("Cannot create the shopping cart for this account!");
                }
                
                header('Location: index.php');
            }
        }

        //print_r($result);
    }

?>