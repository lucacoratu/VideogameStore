<?php
    //Get the session and get the account details from the database
    session_start();
    
    if(isset($_SESSION['username'])){    
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

        $username = $_SESSION['username'];

        //Query the database
        $sql = "select Username, Email, DisplayName, Funds from accounts where Username = '$username'";
        $result = $conn->query($sql);

        if($result->num_rows != 0)
            $row = $result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="manageaccounts.css">
        <script src="manageaccounts.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
        <title>
            Video Game Store
        </title>
    </head>
    <body>
        <div class="navbar">
            <a href="profile.php"> 
                <b>Profile</b>
            </a>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <div class="manage-container">
            <table class="accounts-table">
                <thead>
                    <td>Username</td>
                    <td>Register date</td>
                    <td>Admin</td>
                </thead>
                <?php
                    //Query the database
                    $sql = "select Username, JoinDate, AccTypeID from accounts";
                    $result = $conn->query($sql);

                    while($row = $result->fetch_assoc()){
                        echo '<tr id="row">';
                        echo '<td>'.$row['Username'].'</td><td>'.$row['JoinDate'].'</td>';
                        if($row['AccTypeID'] == 2){
                            echo '<td><input type="checkbox" id="checkbox" checked onclick="checkboxclicked(this)"></td>';
                        }
                        else{
                            echo '<td><input type="checkbox" id="checkbox" onclick="checkboxclicked(this)"></td>';
                        }
                        echo '</tr>';
                    }
                ?>
            </table>
        </div>
    </body>
</html>