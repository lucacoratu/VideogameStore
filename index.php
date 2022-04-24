<!DOCTYPE html>
<html id="html_page">
    <link rel="stylesheet" href="homestyle.css">
    <head>
        <title>
            Video Game Store
        </title>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <!-- Boxicons CSS -->
        <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css">
        <script src="index.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
   
        <?php
            if (!isset($_SESSION)) { 
                //echo "Session is not set yet";
                session_start();
            }
        ?>
   
    </head>
    
    <body class="main_page_body" id="main_body">
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
            <div class="dropdown">
              <?php
                //Check if the session exists else don't show the library
                if(!isset($_SESSION['username'])){
                    //User is not logged in
                    //echo '<script>alert("Session didnt start!")</script>';
                }
                else{
                    echo '
                    <button class="dropbtn"> 
                    <b>Store</b>
  <!--                 <i class="fa fa-caret-down"></i> -->
                </button>
                    <div class="dropdown-content">
                        <a href="library.php">Library</a>
                        <a href="#">Link 2</a>
                        <a href="#">Link 3</a>
                    </div> ';
                }
              ?>
<!--               <div class="dropdown-content">
                <a href="library.html">Library</a>
                <a href="#">Link 2</a>
                <a href="#">Link 3</a>
              </div> -->
            </div>
            <div class="search-input">
                <input id="search_text" type="search" placeholder="Search..">
            </div>
            <?php
                if(!isset($_SESSION['username'])){
                    echo '<div class="login">
                        <a href="login.html">
                            <b>Login</b>
                        </a>
                    </div>
                    <div class="register">
                        <a href="register.html">
                            <b>Sign up</b>
                        </a>
                    </div>';
                }
                else{
                    echo '    
                        <div class="login">        
                            <a href="profile.php">
                                <b>Profile</b>
                            </a>
                        </div>
                        <a class="cart" href="cart.php">
                            <b>Cart</b>
                        </a>';
                    if($_SESSION['acc_type'] == '2'){
                        echo '
                        <a class="create" href="create.php">
                            <b>Create</b>
                        </a>';
                    }
                    echo '
                        <a class="logout" href="logout.php">
                            <b>Logout</b>
                        </a>';

                }
            ?>
            <div class="dropdown-media">
                <button class="dropbtn-media"> 
                    <b>||||</b>
                </button>
                <div class="dropdown-content-media">
                    <?php
                        if (!isset($_SESSION['username'])){
                           echo '<a class="login" href="login.html">Login</a>
                            <a class="register" href="register.html">Sign Up</a>';
                        }
                        else{
                            echo '                  
                                <a class="profile" href="profile.php">Profile</a>
                                <a class="cart" href="cart.php">Cart</a>
                                <a class="create" href="create.php">Create</a>
                                <a class="logout" href="logout.php">Logout</a>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <!-- Grid for the main page games-->
        <div id="games" class="games-grid">
            <?php
                //Take the game previews and show them in the main page
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

                //Select the previews
                $sql = "select Name,ShortDescription,PreviewPhotoPath from gamepreviews";
                $result = $conn->query($sql);
                if($result->num_rows == 0){
                    echo "<div style=\"color: whitesmoke\">No games can be shown!\n";
                    die();
                }

                while($row = $result->fetch_assoc()){
                    $photopath = $row['PreviewPhotoPath'];
                    $name = $row['Name'];
                    $shortdesc = $row['ShortDescription'];
                    echo "<div class=\"games-grid-item\">";
                    echo "<a class=\"content-grid\" href=\"gamepage.php?Name=$name\">";
                    echo "<img class=\"games-grid-picture\" src=\"$photopath\">";
                    echo "<p class=\"games-grid-description\">$shortdesc</p>";
                    echo "<label id=\"game_name\" class=\"games-grid-title\">$name</label>";
                    echo "</a></div>";
                }
            ?>
<!--             <div class="games-grid-item">
                <a class="content-grid" href="gamepage.html">
                    <img class="games-grid-picture" src="Resources/elder_ring.jpg">
                    <p class="games-grid-description">THE NEW FANTASY ACTION RPG. Rise, Tarnished, and be guided by grace to brandish the power of the Elden Ring and become an Elden Lord in the Lands Between.</p>
                    <label id="game_name" class="games-grid-title">Elder Ring</label>
                </a>
            </div> -->
        </div>  
        <!--*****************************-->
        <div class="footer-div" id="footer_div">
        <hr class="footer-line">
        <footer class="footer">
            <div class="footer-segment">
                <h>About</h>
                <p>Development process<a href="README.txt">here</a></p>   
            </div>
            <div class="footer-segment">
                <h>Contact</h>
                <p>Phone: +40743227401</p>
                <p>Email: lucacoratu@gmail.com</p>
            </div>
            <div class="footer-segment">
                <h>Developers</h>
                <p>Coratu Luca C113C</p>
            </div>
        </footer>
        </div>
    </body>
</html>
