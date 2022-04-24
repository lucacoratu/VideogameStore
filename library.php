<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="librarystyle.css"></link>
        <script src="libraryscript.js" defer></script>
    </head>
    <body>
        <?php
            //Check if the user is connected
            session_start();

            if(!isset($_SESSION['username'])){
                die("Cannot have a library when u are not logged in!");
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
            $usernameEscaped = mysqli_real_escape_string($conn, $username);
        ?>
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <div class="sidebar-iframe">
            <div class="sidebar">
                <br>
                <label> My Games </label>
                <hr color="rebeccapurple" style="height: 1px;">
                <br>
                <div class="library-games">
                    <?php
                        //Get the games that are in the library
                        $sql = "select games.Nume from accountlibrary
                        inner join games 
                        on games.ID = accountlibrary.GameID
                        inner join accounts
                        on accounts.ID = accountlibrary.AccID
                        where accounts.Username = '$usernameEscaped'";

                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()){
                            echo '<button onclick="gameButtonClicked(this)">'.$row['Nume'].'</button>';
                        }
                    ?>
<!--                     <button onclick="gameButtonClicked()">Game name</button>
                    <button onclick="gameButtonClicked()">Game name</button>
                    <button onclick="gameButtonClicked()">Game name</button>
                    <button onclick="gameButtonClicked()">Game name</button> -->
                </div>
            </div>
            <iframe id="game-frame">
            </iframe>
        </div>
    </body>
</html>