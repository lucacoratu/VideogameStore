<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="createstyle.css">
        <script src="createscript.js" defer></script>

        <?php
            //check the session
            session_start();
            
            if(!isset($_SESSION['username'])){
                //Alert that the user is not connected
                //Go back to the main page
                header('Location: index.php');
                exit();
            }
            else{
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
            }
        ?>
    </head>
    <body>
        <div class="navbar">
            <a href="index.php"> 
                <b>Home</b>
            </a>
        </div>
        <form class="game-form" enctype="multipart/form-data" method="POST" action="creategame.php">
            <div class="label-input"> 
                <label>
                    Name:
                </label>
                <input placeholder="Insert game name" name="game_name" required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Short Description:
                </label>
                <input placeholder="Insert short description" maxlength="300" name="short_desc" required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Preview Image:
                </label>
                <input type="file" accept="image/*" name="preview" required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Tag:
                </label>
                <select class="select-tags" id="select-tags" size="5" name="tags[]" multiple>
                    <?php
                        //Select the available tags from the database
                        //List them in the select-tags as option
                        $sql = "select Nume from gametags";
                        $result = $conn->query($sql);

                        if($result->num_rows != 0){
                            while($row = $result->fetch_assoc()){
                                echo "<option>".$row['Nume']."</option>";
                            }
                        }
                        else{
                            echo '<option>Tags unavailable!</option>';
                        }
                    ?>
                </select>
            </div>
            <p class="tags-text">Multiple tags can be selected by holding CTRL</p>
            <div class="label-input"> 
                <label>
                    Release Date:
                </label>
                <input type="date" name="release_date" required>
            </div>
            <div class="label-input"> 
                <label>
                    Developer:
                </label>
                <input placeholder="Insert developer name" name="developer" required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Photos:
                </label>
                <input type="file" accept="image/*" name="photos[]" multiple required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Videos:
                </label>
                <input type="file" accept="video/*" name="videos[]" multiple required>
                </input>
            </div>
            <div class="label-input"> 
                <label>
                    Price:
                </label>
               <!--  <input type="text" placeholder="Insert price in USD (max 100$)" maxlength="3"> -->
               <input type="number" min="0.00" max="100.00" step="0.01" name="price" placeholder="Insert price"/>
                </input>
            </div>
            <label class="tags-text">More details about the game</label>
            <button type="button" onclick="addDetailsClicked(this)">Add Detail</button>
            <button type="button" onclick="removeDetailsClicked(this)">Remove Detail</button>
            <div class="title-content-container" id="details-container">
                <div class="title-content">
                    <div class="title-element">
                        <label>Title 1:</label>
                        <input type="text" maxlength="300" id="title1" name="title1" required>
                    </div>
                    <div class="content-element">
                        <label>Content 1:</label>
                        <input type="text" maxlength="2000" id="content1" name="content1" required>
                    </div>
                </div>
            </div>
            <div class="label-input"> 
                <label>
                    Developer Description:
                </label>
               <!--  <input type="text" placeholder="Insert price in USD (max 100$)" maxlength="3"> -->
               <input type="text" maxlength="1000" placeholder="Insert developer description" name="dev_desc" required>
                </input>
            </div>
            <p style="color: white; font-size: 18px; text-align: center;">
                System Requirements
            </p>
            <div class="requirements-tables">
                <table style="width: 100%; color: whitesmoke;">
                    <thead>
                        <td style="width: 40%; font-size: 18px; text-align: center;">Minimum</td>
                    </thead>
                    <tr>
                        <td>Requires a 64 bit processor and operating system</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">OS:</label> 
                                <input placeholder="Insert OS" maxlength="100" style="width: 70%;" name="min_os" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Processor:</label> 
                                <input placeholder="Insert processor" maxlength="200" style="width: 70%;" name="min_processor" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Memory:</label> 
                                <input placeholder="Insert memory" maxlength="200" style="width: 70%;" name="min_memory" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Graphics:</label> 
                                <input placeholder="Insert graphics" maxlength="200" style="width: 70%;" name="min_graphics" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">DirectX:</label> 
                                <input placeholder="Insert DirectX" maxlength="200" style="width: 70%;" name="min_directx" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Storage:</label> 
                                <input placeholder="Insert storage" maxlength="200" style="width: 70%;" name="min_storage" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Additional Notes:</label> 
                                <input placeholder="Insert additional notes" maxlength="200" style="width: 70%;" name="min_additional" required></input>
                            </div>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; color: whitesmoke;">
                    <thead>
                        <td style="width: 50%; font-size: 18px; text-align: center;">Recomended</td>
                    </thead>
                    <tr>
                        <td>Requires a 64 bit processor and operating system</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">OS:</label> 
                                <input placeholder="Insert OS" maxlength="100" style="width: 70%;" name="rec_os" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Processor:</label> 
                                <input placeholder="Insert processor" maxlength="200" style="width: 70%;" name="rec_processor" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Memory:</label> 
                                <input placeholder="Insert memory" maxlength="200" style="width: 70%;" name="rec_memory" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Graphics:</label> 
                                <input placeholder="Insert graphics" maxlength="200" style="width: 70%;" name="rec_graphics" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">DirectX:</label> 
                                <input placeholder="Insert DirectX version" maxlength="200" style="width: 70%;" name="rec_directx" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Storage:</label> 
                                <input placeholder="Insert storage" maxlength="200" style="width: 70%;" name="rec_storage" required></input>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="table-cell">
                                <label style="width: 30%;">Additional Notes:</label> 
                                <input placeholder="Insert additional notes" maxlength="200" style="width: 70%;" name="rec_additional" required></input>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <button type="submit">Create Game</button>
        </form>
    </body>
</html>