<!DOCTYPE html>
<html>
    <head>
        <link href='https://fonts.googleapis.com/css?family=Dekko' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
        <link rel="stylesheet" href="cartstyle.css">
        <script src="cart.js" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
    </head>
    <body class="cart-body">
        <?php
            //Get the session
            session_start();

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
        ?>
        <!-- In this div are the elements from the navigation bar-->
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <!-- Cart elements -->
        <div class="cart-body-container">
            <div class="cart-container">
                <div class="Header">
                    <h3 class="Heading">Shopping Cart</h3>
                    <h5 class="Action" onclick="removeAllItemsFromCart()">Remove all</h5>
                </div>
                <?php
                    $usernameEscaped = mysqli_real_escape_string($conn, $_SESSION['username']);
                    
                    //Get the account cart id
                    $sql = "select shoppingCarts.ID from accounts 
                    inner join shoppingcarts
                    on shoppingCarts.AccID = accounts.ID
                    where accounts.Username = '$usernameEscaped'";
                    $result = $conn->query($sql);
                    if($result->num_rows == 0){
                        die("Cannot get the id of the shopping cart");
                    }
                    $cartId = $result->fetch_assoc()['ID'];

                    //Get all of the games from the cart
                    $sql = "select games.Nume, gamepreviews.PreviewPhotoPath, games.Price from gamecart
                    inner join games
                    on games.ID = gamecart.GameID
                    inner join gamepreviews
                    on gamepreviews.GameID = gamecart.GameID
                    where gamecart.CartID = $cartId";

                    $result = $conn->query($sql);
                    if($result->num_rows == 0) {
                        //Shopping cart is empty
                        echo '<p style="text-align: center; color: whitesmoke; font-size: 20px;">Shopping cart is empty!</p>';
                    }
                    else{
                        while($row = $result->fetch_assoc()){
                            echo '<div class="cart-item" id="cart-item">';
                            echo '<div class="image-box">'.PHP_EOL;
                            echo '<img src="'.$row['PreviewPhotoPath'].'" style="height: 120px;"/>';
                            echo '</div>';
                            echo '<div class="about">';
                            echo '<h1 class="title">'.$row['Nume'].'</h1>';
                            //TO DO multiple editions of the game
                            echo '<h3 class="subtitle">Definitive Edition</h3>';
                            echo '</div>';
                            echo '<div class="prices">';
                            echo '<div class="amount">'.$row['Price'].' $</div>';
                            echo '<div class="remove"><u onclick="removeItemFromCart(this)">Remove</u></div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                ?>
<!--                 <div class="cart-item">
                    <div class="image-box">
                        <img src="Resources/Assassin's Creed Odyssey/Photos/image1.jpg" style="height: 120px" />
                    </div>
                    <div class="about">
                        <h1 class="title">Assassin's Creed Origins</h1>
                        <h3 class="subtitle">Definitive Edition</h3>
                    </div>
                    <div class="counter"></div>
                    <div class="prices">
                        <div class="amount">$30.99</div>
                        <div class="remove"><u>Remove</u></div>
                    </div>
                </div> -->

                <hr class="checkout-line"> 
                <div class="checkout">
                <div class="total">
                <div>
                <div class="Subtotal">Sub-Total</div>
                <div class="items">
                    <?php
                        //Get the number of items in the cart
                        $sql = "select count(*) as Numar from gamecart where CartID = $cartId";
                        $result = $conn->query($sql);
                        if($result->num_rows == 0){
                            //There are 0 items in the cart
                            echo '0 items';
                        }
                        else{
                            $noProducts = $result->fetch_assoc()['Numar'];
                            $string = ($noProducts == 1) ? " item" : " items";
                            echo $noProducts.$string;
                        }
                    ?>
                </div>
                </div>
                <div class="total-amount">
                    <?php
                        //Get the total price of the cart (round the total price to 2 decimal places)
                        $sql = "select round(sum(games.Price), 2) as Total from gamecart
                        inner join games
                        on games.ID = gamecart.GameID
                        where gamecart.CartID = $cartId";

                        $result = $conn->query($sql);
                        if($result->num_rows == 0){
                            echo '$0.0';
                        }
                        else{
                            $total = $result->fetch_assoc()['Total'];
                            echo '$'.$total;
                        }

                    ?>
                </div>
                </div>
                <button class="button" onclick="checkoutCart()">Checkout</button>
                </div>
            </div>
        </div>
    </body>
</html>