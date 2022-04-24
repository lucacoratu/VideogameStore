<?php
    //Check if the user is connected to an account
    session_start();

    if(!isset($_SESSION['username'])){
        die("Cannot rate the game if you are not logged in!");
    }

    //Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gamestore";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Get the game name and the grade from the post supervariable
    $gameName = $_POST['gamename'];
    $grade = $_POST['grade'];

    //Escape the gameName
    $gameNameEscaped = mysqli_real_escape_string($conn, $gameName);

    //Get the game id
    $sql = "select ID from games where Nume = '$gameNameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot find the game in the database!");
    }
    $game_id = $result->fetch_assoc()['ID'];

    //Get the account id
    $username = $_SESSION['username'];
    $usernameEscaped = mysqli_real_escape_string($conn, $username);
    $sql = "select ID from accounts where Username = '$usernameEscaped'";
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        die("Cannot find the account!");
    }

    $accId = $result->fetch_assoc()['ID'];

    //Insert the grade into the reviews table
    $sql = "insert into reviews(AccID, GameID, Grade) values($accId, $game_id, $grade)";
    $result = $conn->query($sql);

    if($result == false){
        die("Cannot insert the grade into the database!");
    }

    echo "Thank you for your review!";
?>