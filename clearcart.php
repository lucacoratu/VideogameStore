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

    if(!isset($_SESSION['username'])){
        die("You have to be connected to an account in order to clear the cart!");
    }

    //Get the account id
    $username = $_SESSION['username'];
    $usernameEscaped = mysqli_real_escape_string($conn, $username);

    $sql = "select shoppingCarts.ID from shoppingcarts
    inner join accounts
    on accounts.ID = shoppingcarts.AccID
    where accounts.Username='$usernameEscaped'";

    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot find the cart id in the database!");
    }

    $cartId = $result->fetch_assoc()['ID'];

    //Delete all items in the gamecart table with the cartid = $cartid
    $sql = "delete from gamecart where CartID = $cartId";
    $result = $conn->query($sql);
    if($result == false){
        die("Cannot delete the items in the cart!");
    }

    echo "Cart has been cleared!";
?>