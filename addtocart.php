<?php
    //Get the session
    session_start();

    if(!isset($_SESSION['username'])){
        die("Cannot add a game to cart if you are not logged in!");
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

    //Get the game name from POST supervariable
    $gameName = $_POST['gamename'];
    $gameNameEscaped = mysqli_real_escape_string($conn, $gameName);

    //Get the game id
    $sql = "select ID from games where Nume='$gameNameEscaped'";
    $result = $conn->query($sql);

    //Check if the game exists in the database
    if($result->num_rows == 0){
        die("Game does not exist in the database!");
    }
    $gameId = $result->fetch_assoc()['ID'];

    //Get the account id
    $usernameEscaped = mysqli_real_escape_string($conn, $_SESSION['username']);
    $sql = "select ID from accounts where Username = '$usernameEscaped'";
    $result = $conn->query($sql);
    //Check if the account exists
    if($result->num_rows == 0){
        die("Cannot find the account in the database!");
    }
    $accId = $result->fetch_assoc()['ID'];

    //Check if the game is not already in the cart of this account
    $sql = "select gamecart.GameID from gamecart 
    inner join shoppingcarts 
    on shoppingcarts.ID = gamecart.CartID 
    inner join accounts 
    on accounts.ID = shoppingcarts.AccID 
    where accounts.ID = $accId and gameCart.gameID = $gameId";
    
    $result = $conn->query($sql);
    if($result->num_rows != 0){
        die("Game is already in the shopping cart!");
    }

    //Get the cart id of the account
    $sql = "select ID from shoppingcarts where AccID = $accId";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot find the cart for the account!\n");
    }
    $cartId = $result->fetch_assoc()['ID'];
    
    //Add the game to the cart
    $sql = "insert into gamecart (GameID, CartID) values($gameId, $cartId)";
    $result = $conn->query($sql);
    if($result == false){
        die("Cannot add the game to cart!");
    }

    echo "$gameName has been added to the cart";
?>