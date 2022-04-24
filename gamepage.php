<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="gamepagestyle.css">
        <!-- Font Awesome Icon Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
        <script src="gamepage.js" defer></script>
        <title>Video Game Store</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <?php
            //Check the session
            session_start();

            if(!isset($_SESSION['username'])){
                //Load the guest variant of the page
                $_SESSION['guest'] = true;
            }

            //Get the game name from the url
            $game_name = $_GET['Name'];
            $_SESSION['current-game'] = $game_name;
            

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

            $game_name_escaped = $conn->real_escape_string($game_name);
            $sql = "select ID from games where Nume='$game_name_escaped'";
            $result = $conn->query($sql);
            if($result->num_rows == 0){
                die("No game with this name can be found!\n");
            }

            $game_id = $result->fetch_assoc()['ID'];
        ?>
    </head>
    <body class="game-body">
        <div class="navbar" id="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
            <?php
                //Check if the user is connected
                if(isset($_SESSION['username'])){
                   echo '<a href="cart.php"> 
                        <b>Cart</b>
                    </a>';
                }
            ?>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <p class="game-title" id="game_name">
            <?php 
                echo "$game_name"; 
            ?>
        </p>
        <br>
        <div class="video-container-styling">
            <div class="video-container">
                <div class="video">
                    <video id="video_shower" class="current-video">
                        <!-- <source src="Resources/videos/ac_origins_trailer.mp4" type="video/mp4">  -->
                        <source src = "
                        <?php
                            //select the first video found in the database for this game
                            $sql = "select Path from gamevideos where GameID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the path for the video");
                            }
                            $videoPath = $result->fetch_assoc()["Path"];
                            echo $videoPath;
                        ?>
                        " type="video/webm">
                    </video>
                    <img id="image_shower" class="image-shower" hidden>
                    <div id="list-images" class="videos-photos">
                        <!--  <source src="Resources/videos/ac_origins_trailer.mp4" type="video/mp4"> -->
                        <?php
                                //select the first video found in the database for this game
                                $sql = "select Path from gamevideos where GameID=$game_id";
                                $result = $conn->query($sql);
                                if($result->num_rows == 0){
                                    die("Cannot get the path for the video");
                                }
                                
                                while($row = $result->fetch_assoc()){
                                    echo '<video width="100px" height="100px" onclick="imageClicked(event)">';
                                    echo '<source src = "';
                                    echo $row['Path'];
                                    echo '" type="video/webm">';
                                    echo '</video>';
                                }
                        ?>
<!--                         <img src="Resources/ac_origins.jpg" width="100px" height="80px" onclick="imageClicked(event)">
                        <img src="Resources/ac_origins2.jpg" width="100px" height="80px" onclick="imageClicked(event)">
                         <img src="./Resources/ac_origins3.jpg" width="100px" height="80px" onclick="imageClicked(event)"> -->
                        <!-- <img src="Resources/ac_origins4.jpg" width="100px" height="80px" onclick="imageClicked(event)"> -->
                        <?php
                            $sql = "select Path from gamephotos where GameID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("select failed for gamephotos!\n");
                            }

                            while($row = $result->fetch_assoc()){
                                echo '<img src="';
                                echo $row['Path'];
                                echo '" width="100px" height="100px" onclick="imageClicked(event)">';
                            }
                        ?>
                    </div>
                </div>
                <div class="video-text">
                    <img width="100%" height="140px" src=
                            <?php
                                $sql = "select PreviewPhotoPath from gamepreviews where GameID = $game_id";
                                $result = $conn->query($sql);
                                if($result->num_rows == 0){
                                    //Failed to get the photo path
                                    die("Could not get the preview photo path!\n");
                                }
                                echo '"';
                                echo $result->fetch_assoc()['PreviewPhotoPath'];
                                echo '"';
                            ?>
                    >
                    <p>
                        <?php
                            //Select the short description
                            $sql = "select ShortDescription from GamePreviews where GameID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the short description of the game");
                            }
                            $short_desc = $result->fetch_assoc()['ShortDescription'];
                            echo "$short_desc";
                        ?>
                    </p>
                    <br>
                    <label style="display:block; margin: auto; width: 50%; text-align: center;">
                        Tags
                    </label>
                    <div class="game-tags">
                        <?php
                            $sql = "select Nume from gamestag inner join gametags on gamestag.TagID = gametags.ID where gamestag.GameID = $game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the tags for the game!");
                            }

                            $i = 0;
                            while($row = $result->fetch_assoc()){
                                echo "<p>".$row['Nume']."</p>";
                                if($i == 3)
                                    break;
                                $i++;
                            }
                        ?>
                    </div>
                    <label>
                        Reviews:
                        <?php
                            //If there are more reviews between 7 and 10 than between 0 and 4 and than between 4 and 7
                            //Write mostly positive
                            //If there are more reviews between 4 and 7 than between 0 and 4 and than between 7 and 10
                            //Write moderate
                            //Else
                            //Write mostly negative 
                            //Determine the average of grades and compare the average with these values

                            //If there aren't any reviews yet then write Not enough reviews
                            $sql = "select Grade from reviews where GameID = $game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                echo "Not enough reviews";
                            }
                            else{

                            $sql = "select avg(Grade) as Average from reviews
                            where GameID = $game_id";
                            $result = $conn->query($sql);
                            
                            $average = $result->fetch_assoc()['Average'];
                            
                            if($average >= 7 and $average <= 10){
                                echo "Mostly positive";
                            }
                            elseif($average >= 4 and $average < 7){
                                echo "Moderate";
                            }
                            elseif($average >= 1 and $average < 4){
                                echo "Mostly negative";
                            }
                            else{
                                echo "Not enough reviews";
                            }
                        }
                        ?>
                    </label>
                    <br>
                    <label>
                        Release Date:
                        <?php
                            $sql = "select day(ReleaseDate) as Day, monthname(ReleaseDate) as Month, year(ReleaseDate) as Year from games where ID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the release date of the game!\n");
                            }
                            $row = $result->fetch_assoc();
                            echo $row['Day']." ".$row['Month']." ".$row['Year'];
                        ?>
                    </label>
                    <br>
                    <label>
                        Developer:
                        <?php
                            $sql = "select Developer from games where ID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the release date of the game!\n");
                            }
                            $row = $result->fetch_assoc();
                            echo $row['Developer'];
                        ?>
                    </label>
                    <br>
                    <label>
                        Publisher:
                        <?php
                            echo $row['Developer'];
                        ?>
                    </label>
                </div>
            </div>
        </div>
        <?php
            if(!isset($_SESSION['username']))
                echo '<p class="login-to-purchase">Sign in to purchase this game</p>';
        ?>
        <br>
        <div class="purchase-container">
            <div class="purchase-card">
                <label class="buy-message">
                    <?php
                        echo "Buy ".$game_name;
                    ?>
                </label>
                <div class="price-text-container">
                    <label class="price-label">
                        <?php
                            $sql = "select Price from games where ID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the release date of the game!\n");
                            }
                            $row = $result->fetch_assoc();
                            if($row['Price'] != 0)
                                echo $row['Price']."$";
                            else
                                echo "Free";
                        ?>
                    </label>
                    <?php 
                        if(isset($_SESSION['username'])){
                            $usernameEscaped = $_SESSION['username'];

                            $sql = "select games.ID from accountlibrary
                            inner join accounts
                            on accounts.ID = accountlibrary.AccID
                            inner join games
                            on games.ID = accountlibrary.GameID
                            where accounts.Username = '$usernameEscaped' and games.ID = $game_id";

                            $result = $conn->query($sql);

                            //If the game is not owned by the user
                            if($result->num_rows == 0){
                            echo '<button onclick="addGameToCart(event)">
                                    Add to Cart
                                </button>';
                            }
                            else{
                                echo '<button>
                                    Game Owned
                                </button>';
                            }

                            //If the game is owned by the user
                        }
                        else{
                            //If the user is guest
                            if(isset($_SESSION['guest'])){
                                echo '<button>
                                        Login to purchase
                                </button>';
                            }
                         }


                    ?>
                </div>
            </div>
            <div class="purchase-card">
                <label class="buy-message">Another version of the game</label>
                <div class="price-text-container">
                    <label class="price-label">
                        <?php
                            $sql = "select Price from games where ID=$game_id";
                            $result = $conn->query($sql);
                            if($result->num_rows == 0){
                                die("Cannot get the release date of the game!\n");
                            }
                            $row = $result->fetch_assoc();
                            if($row['Price'] != 0)
                                echo $row['Price']."$";
                            else
                                echo "Free";
                        ?>
                    </label>
                    <button>
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="footer">
            <p class="details-title">MORE DETAILS</p>
            <hr class="line">
            <div class="more-details">
                <?php
                    //Take all the titles and contents and list them
                    $sql = "select Title, Text as Content from gamedetails where GameID=$game_id";
                    $result = $conn->query($sql);
                    if($result->num_rows == 0){
                        die("Cannot get more details about the game");
                    }

                    while($row = $result->fetch_assoc()){
                        echo '<p class="details-paragraph">'.$row["Title"].'</p><br><p class="details-paragraph">'.$row["Content"].'</p><br>';
                    }
                ?>
            </div>
            <br>
            <p class="details-title">DEVELOPER DESCRIPTION</p>
            <hr class="line">
            <p class="details-paragraph">
                <?php
                    $sql = "select DeveloperDescription from games where ID=$game_id";
                    $result = $conn->query($sql);
                    if($result->num_rows == 0){
                        die("Cannot get more details about the game");
                    }

                    echo $result->fetch_assoc()['DeveloperDescription'];
                ?>
            </p>
            <br>
            <p class="details-title">SYSTEM REQUIREMENTS</p>
            <hr class="line">
            <br>
            <table style="width: 100%; color: whitesmoke;">
                <thead>
                    <td style="width: 40%; font-size: 22px;">Minimum</td>
                    <td style="width: 40%; font-size: 22px;">Recomended</td>
                </thead>
                <?php
                    //Select minimum system requirements
                    $sql = "select * from minsysreq inner join sysrequirements on minsysreq.ID = sysrequirements.MinSysReq where sysrequirements.GameID = $game_id";
                    $result = $conn->query($sql);
                    if($result->num_rows == 0){
                        die("Cannot get the minimum system requirements!\n");
                    }

                    $row_min = $result->fetch_assoc();

                    //Select recommended system requirements
                    $sql = "select * from recsysreq inner join sysrequirements on recsysreq.ID = sysrequirements.MinSysReq where sysrequirements.GameID = $game_id";
                    $result = $conn->query($sql);
                    if($result->num_rows == 0){
                        die("Cannot get the minimum system requirements!\n");
                    }

                    $row_rec = $result->fetch_assoc();
                ?>
                <tr>
                    <td>Requires a 64 bit processor and operating system</td>
                    <td>Requires a 64 bit processor and operating system</td>
                </tr>
                <tr>
                    <td>OS: 
                        <?php
                            echo $row_min["OS"];
                        ?>
                    </td>
                    <td>OS: 
                        <?php
                            echo $row_rec["OS"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Processor: 
                        <?php
                            echo $row_min["Processor"];
                        ?>
                    </td>
                    <td>Processor: 
                        <?php
                            echo $row_rec["Processor"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Memory: 
                        <?php
                            echo $row_min["Memory"];
                        ?>
                    </td>
                    <td>Memory: 
                    <?php
                            echo $row_rec["Memory"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Graphics:                         
                        <?php
                            echo $row_min["Graphics"];
                        ?></td>
                    <td>Graphics: 
                        <?php
                            echo $row_rec["Graphics"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>DirectX: 
                        <?php
                            echo $row_min["DirectX"];
                        ?>
                    </td>
                    <td>DirectX: 
                        <?php
                            echo $row_rec["DirectX"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Storage: 
                        <?php
                            echo $row_min["Storage"];
                        ?>
                    </td>
                    <td>Storage: 
                        <?php
                            echo $row_rec["Storage"];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Additional notes: 
                        <?php
                            echo $row_min["AdditionalNotes"];
                        ?>
                    </td>
                    <td>Additional notes: 
                        <?php
                            echo $row_rec["AdditionalNotes"];
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Review the game if purchased -->
        <?php
            //If the user is connected show this card
            if(isset($_SESSION['username'])){
                //Check if the user has already reviewed this game
                $sql = "select AccID from reviews
                inner join accounts
                on accounts.ID = reviews.AccID
                where accounts.Username = '$usernameEscaped'";

                $result = $conn->query($sql);
                if($result->num_rows == 0) { 

                    echo '
                    <div class="review-card">
                        <p>You purchased the game, let us know your opinion</p>
                        <div class="review-stars">';
                            $i = 0;
                            for($i = 0; $i < 10; $i++){
                                echo '<span class="fa fa-star" id="review_star'.$i.'"></span>';
                            }
                    echo '</div>
                        <button class="review-button" onclick="reviewGame()">Review</button>
                        </div>';
                }
            }
        ?>
        <!-- Write a new comment -->
        <br>
        <?php
            //If the user is not logged in then it shouldn't see the add comment form
            if(isset($_SESSION['username'])){
                echo '<form class="new-comment" method="POST" action="addcomment.php">
                    <p style="color: whitesmoke; border: 2px solid #777;">Share your opinion about this game</p>
                    <textarea rows="10" cols="50" maxlength="300" style="width: 100%; resize: vertical; background-color: #777; color: whitesmoke; font-size: 16px" name="comment_text" required></textarea>
                    <button type="submit" class="addcomment-btn">Add comment</button>
                    </form>';
            }
        ?>
        <!-- comments card -->
        <br>
        <p style="color: white; margin-left:18px">Comments section:</p>
        <br>
        <div class="comments-container">
            <?php
                //Get the comments for this game and list them
                $sql = "select accounts.Username, gamecomments.Text from gamecomments inner join accounts on accounts.ID = gamecomments.AccID where GameID=$game_id";
                $result = $conn->query($sql);
                if($result->num_rows == 0) {
                    if(isset($_SESSION['username'])){
                        echo '<p style="color: whitesmoke; text-align: center; font-weight: 600" >No comments for this game, be the first one to say something!</p>';
                    }
                    else{
                        echo '<p style="color: whitesmoke; text-align:center; font-weight: 600">No comments for this game, log in and be the first to say something!</p>';
                    }
                }
                else{
                    while($row = $result->fetch_assoc()){
                        echo '<div class="comments-card">';
                        echo '<label><b style="color: #9370DB; padding-left: 10px; font-size: 21px;">'.$row['Username'].'</b> said about this game:</label>';
                        echo '<p>'.$row['Text'].'</p>';
                        echo '</div>';
                    }
                }
            ?>
<!--             <div class="comments-card">
                <label>Username said about this game:</label>
                <p>Ceva</p>        
            </div>
            <div class="comments-card">
                <label>Username said about this game:</label>
                <p>Ceva</p>        
            </div> -->
        </div>
    </body>
</html>