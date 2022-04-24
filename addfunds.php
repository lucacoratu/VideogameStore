<?php
    session_start();    

    if(isset($_SESSION['username'])){    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gamestore";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        $acc_name_escaped = mysqli_real_escape_string($conn, $_SESSION['username']);
        
        //Get the account id 
        $sql = "select ID from accounts where Username='$acc_name_escaped'";
        $result = $conn->query($sql);
        if($result->num_rows == 0){
            die("Cannot get the account id!\n");
        }

        $accID = $result->fetch_assoc()['ID'];

        //Add funds to the account if the value is between 0.01 and 100;
        $fundsWanted = $_POST['amount'];
        $fundsWanted = ($fundsWanted > 100.0) ? 100.0 : $fundsWanted;

        if($fundsWanted > 0.0 and $fundsWanted <= 100.0){
            //Get the previous value of the funds
            $sql = "select Funds from accounts where ID=$accID";
            $result = $conn->query($sql);
            if($result->num_rows == 0){
                die("Cannot get the previous funds!\n");
            }
            $prev_funds = $result->fetch_assoc()['Funds'];
            $new_funds = $prev_funds + $fundsWanted;
            //Set a limit for the maximum funds an account can have at a time
            $new_funds = ($new_funds > 400.0) ? 400.0 : $new_funds;

            //Update the accounts table
            $sql = "update accounts set Funds=$new_funds where ID=$accID";
            $result = $conn->query($sql);
            if($result == false){
                die("Cannot update the funds for the account: $accID!\n");
            }
        }
    }

    header('Location: profile.php');
?>