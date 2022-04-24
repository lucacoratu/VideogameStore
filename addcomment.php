<?php
    //Check if the user is connected
    session_start();

    if(!isset($_SESSION['username'])){
        die("Cannot add a comment if u are not logged in!\n");
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

    //Take the input data and the username of the account
    //and add them into the Comments table for the game
    
    //Take the game id
    $game_name = $_SESSION['current-game'];
    $gameNameEscaped = mysqli_real_escape_string($conn, $game_name);
    $sql = "select ID from games where Nume='$gameNameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the game id!\n");
    }
    $gameId = $result->fetch_assoc()['ID'];

    //echo "$game_name\n";

    //Take the acc id
    $accName = $_SESSION['username'];
    $accNameEscaped = mysqli_real_escape_string($conn, $accName);

    $sql = "select ID from accounts where Username='$accNameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot get the account id!\n");
    }

    $accID = $result->fetch_assoc()['ID'];

    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

    $sql = "insert into gamecomments (GameID, AccID, Text) values ($gameId, $accID, '$comment_text')";

    $result = $conn->query($sql);
    if($result == false){
        die("Cannot insert the comment into the database!\n");
    }

    $return_page = "gamepage.php?Name=".$game_name;
    header('Location: '.$return_page);
?>