<?php
    session_start();

    if(!isset($_SESSION['username'])) {
        die("CAnnot remove an item if you are not connected to an account!");
    }

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

    $username = $_SESSION['username'];

    //Get the game name from the POST supervariable
    $gameName = $_POST['gamename'];
    $gameNameEscaped = mysqli_real_escape_string($conn, $gameName);

    //Get the cart id of the user
    $sql = "select shoppingcarts.ID from shoppingcarts
    inner join accounts
    on accounts.ID = shoppingcarts.AccID
    where accounts.Username = '$username'";

    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the cart id!");
    }

    $cartId = $result->fetch_assoc()['ID'];

    //Get the game id
    $sql = "select ID from games where Nume = '$gameNameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the game id!");
    }

    $gameId = $result->fetch_assoc()['ID'];

    //Delete the game from the cart
    $sql = "delete from gamecart where CartID = $cartId and GameID = $gameId";
    $result = $conn->query($sql);
    if($result == false){
        die("Cannot delete the game from the cart!");
    }

    //The delete operation is successful
    echo "$gameName has been removed from the cart!";
?>