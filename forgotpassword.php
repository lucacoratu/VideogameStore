<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="forgotpassword.css">
    </head>
    <body>
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
        </div>
        <hr color="rebeccapurple" style="height: 2px;">
        <form class="forgot-form" method="POST" action="forgot.php">
            <p>Forgot password?</p>
            <label>Username or Email: </label>
            <input type="text" placeholder="Insert username or email" name="username" required>
            <label>New password:</label>
            <input type="password" id="new_password" name="password" placeholder="Insert new password" required>
            <label>Confirm new password:</label>
            <input type="password" id="confirm_new_password" name="confirmpassword" placeholder="Insert new password" required>
            <label>
                <?php
                    session_start();

                    if(isset($_SESSION['message'])){
                        $message = $_SESSION['message'];
                        echo "$message";
                        session_unset();
                    }
                ?>  
            </label>
            <button type="submit" id="submitbtn">Apply changes</button>
        </form>
    </body>
</html>