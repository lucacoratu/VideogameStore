<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="listpurchases.css">
    <title>
        Video Game Store
    </title>

    <?php
        //Check if the user is connected
        session_start();

        if(!isset($_SESSION['username'])){
            die("Cannot see the purchases if you are not connected to an account!");
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
</head>
<body>
    <div class="navbar">
        <a href="profile.php"> 
            <b>Profile</b>
        </a>
    </div>
    <hr color="rebeccapurple" style="height: 2px;">
    <div class="purchases-body">
        <div class="purchases-container">
            <p class="title">Purchase history</p>
            <?php
                //Get the purchases of the account
                $sql = "select games.Nume, day(purchasedetails.OrderDate) as Day, monthname(purchasedetails.OrderDate) as Month, year(purchasedetails.OrderDate) as Year, games.Price from purchases
                inner join accounts
                on accounts.ID = purchases.AccID
                inner join purchasedetails
                on purchasedetails.OrderID = purchases.ID
                inner join games
                on games.ID = purchasedetails.GameID
                where accounts.Username = '$usernameEscaped'
                order by purchasedetails.OrderDate desc";

                $result = $conn->query($sql);

                $i = 1;
                while($row = $result->fetch_assoc()){
                    echo '<div class="purchase">';
                    echo '<label class="idlabel">'.$i.'.</label>';
                    echo '<label class="game-name">'.$row['Nume'].'</label>';
                    echo '<label class="purchase-date">'.$row['Day'].' '.$row['Month'].' '.$row['Year'].'</label>';
                    echo '<label class="game-price">'.$row['Price'].' $</label>';
                    echo '</div>';

                    $i++;
                }
            ?>
<!--             <div class="purchase">
                <label class="idlabel">1.</label>
                <label class="game-name">Ceva</label>
                <label class="pruchase-date">01 Oct 2020</label>
            </div>  -->
        </div>
    </div>
</body>
</html>