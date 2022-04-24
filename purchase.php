<?php
    //Check that the user is logged in
    session_start();

    if(!isset($_SESSION['username'])){
        die("Cannot purchase games if you are not logged in!");
    }

    //Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gamestore";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Get the games in the cart
    $username = $_SESSION['username'];
    $usernameEscaped = mysqli_real_escape_string($conn, $username);

    //Get the account id
    $sql = "select ID from accounts where Username = '$usernameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the account id!");
    }
    $accId = $result->fetch_assoc()['ID'];

    //Get the cart id
    $sql = "select shoppingcarts.ID from shoppingcarts
    inner join accounts
    on accounts.ID = shoppingcarts.AccID
    where accounts.Username = '$usernameEscaped'";

    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot find the cart id of the account!");
    }

    $cartId = $result->fetch_assoc()['ID'];

    //Check if the cart is empty
    $sql = "select GameID from gamecart where CartID = $cartId";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        //The cart is empty 
        die("Cart is empty!");
    }

    //Get the total of the cart
    $sql = "select round(sum(games.Price), 2) as Total from gamecart
    inner join games
    on games.ID = gamecart.GameID
    where gamecart.CartID = $cartId";

    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the total of the cart!");
    }

    $total = $result->fetch_assoc()['Total'];

    //Get the current funds of the account
    $sql = "select Funds from accounts where Username='$usernameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the current funds of the account!");
    }

    $funds = $result->fetch_assoc()['Funds'];

    //Check if the account has sufficient funds
    if($funds < $total){
        die("Cannot checkout the cart, insufficient funds!");
    }

    //Update the funds
    $newFunds = $funds - $total;
    $sql = "update accounts set Funds=$newFunds where Username='$usernameEscaped'";
    $result = $conn->query($sql);
    if($result == false){
        die("Cannot update the funds of the account!");
    }

    //Insert the purchase in the purchases table
    $sql = "insert into purchases (AccID) values($accId)";
    $result = $conn->query($sql);
    if($result == false){
        die("Cannot insert the purchase in the purchases table!");
    }
    //Get the order id
    $orderId = $conn->insert_id;

    //Add the purchased games to the library
    $sql2 = "select GameID from gamecart where CartID = $cartId";
    $result2 = $conn->query($sql2);

    while($row = $result2->fetch_assoc()){
        //Add the games in the library
        $gameId = $row['GameID'];
        $sql = "insert into accountlibrary (AccID, GameID) values($accId, $gameId)";
        $result = $conn->query($sql);
        if($result == false){
            die("Cannot insert the game into thew library, game id: $gameId");
        }

        //Store the details of the checkout in the purchases table
        $sql = "insert into purchasedetails (OrderID, GameID) values($orderId, $gameId)";
        $result = $conn->query($sql);
        if($result == false){
            die("Cannot insert the details of the purchase in the database!");
        }
    }

    //Remove all of the items from the cart
    $sql = "delete from gamecart where CartID = $cartId";
    $result = $conn->query($sql);
    if($result == false) {
        die("Cannot remove the items from the cart!");
    }

    echo 'Thank you for your purchase!';
?>