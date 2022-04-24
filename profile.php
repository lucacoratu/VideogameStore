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
        <link rel="stylesheet" href="profilestyle.css">
        <script src="profilescript.js" defer></script>
        <title>
            Video Game Store
        </title>
    </head>
    <body>
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
            <a style="margin-left: auto;" href="listpurchases.php">
                <b>Purchases</b>
            </a>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <div class="profile-body">
            <div class="profile-container">
                <div class="profile-picture">
                    <img src="Resources/user_icon.png">
                </div>
                <table>
                    <tr>
                        <td>Username</td>
                        <td>
                            <?php
                                echo "$username";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>
                            <?php
                                echo $row['Email'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Display Name</td>
                        <td>
                            <?php
                                echo $row['DisplayName'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Funds</td>
                        <td>
                            <?php
                                echo $row['Funds']." $";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>Sos. Viilor</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="update-details">Change details</button>
                        </td>
                    </tr>
                </table>
                <!-- Form for adding funds to the account -->
                <form class="addfunds-form" method="POST" action="addfunds.php">
                    <label>Amount:</label>
                    <input name="amount" type="number" min="0" max="100" step="0.01">
                    <button>Add Funds</button>
                </form>
            </div>
        </div>
    </body>
</html>