<?php
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

    session_start();

    if(!isset($_SESSION['username'])){
        die("User is not logged in!");
    }

    if($_SERVER["REQUEST_METHOD"] = "POST"){
        //Take all of the inputs
        $game_name_no_escape = $_POST["game_name"];
        $game_name = $conn->real_escape_string($_POST["game_name"]);

        //Check if the game doesn't already exist
        $sql = "select Nume from games where Nume='$game_name'";
        $result = $conn->query($sql);
        if($result->num_rows != 0){
            //TO DO... Add the alert to notify that the game already exists
            die("Game already exists!");
        }

        $price = $conn->real_escape_string($_POST['price']);
        $release_date = $conn->real_escape_string($_POST['release_date']);
        //echo "$release_date\n";
        $developer = $conn->real_escape_string($_POST['developer']);
        $dev_description = $conn->real_escape_string($_POST['dev_desc']);
        $short_desc = $conn->real_escape_string($_POST["short_desc"]);

        //Insert into game table the name, price, release date, developer, short_desc
        $sql = "insert into games(Nume, Price, ReleaseDate, Developer, DeveloperDescription) values('$game_name', $price, '$release_date', '$developer', '$dev_description')";
        //echo "$sql\n";

        $result = $conn->query($sql);
        if($result == false){
            echo "Could not insert into the games table!\n";
        }
        
        //Create a directory for the game where it's resources will be saved
        $uploaddir = "Resources/".$game_name_no_escape;
        $result = mkdir($uploaddir, "0777");

        $preview_photo = $_FILES['preview']['name'];
        
        $uploadfile = $uploaddir . "/" . basename($preview_photo);

        if(move_uploaded_file($_FILES['preview']['tmp_name'], $uploadfile)){
            //File is valid 
            echo 'File was succesfully uploaded';
        }
        else{
            echo 'Possible file attack';
        }

        //Get the inserted game id
        $sql = "select ID from games where Nume='$game_name'";
        $result = $conn->query($sql);

        if($result->num_rows == 0){
            //Game was not inserted
            die("Game was not inserted, cannot continue!\n");
        }

        $game_id = $result->fetch_assoc()['ID'];
        $uploadfile = $conn->real_escape_string($uploadfile);
        //Save the path in the game preview table
        $sql = "insert into gamepreviews (GameID, Name, ShortDescription, PreviewPhotoPath, PreviewPhotoContent) values ('$game_id', '$game_name', '$short_desc', '$uploadfile', 'NULL')";
        $result = $conn->query($sql);
        if($result == false){
            die("Preview could not be inserted!\n");
        }

        //Get the tags selected for the game
        $tags = $_POST['tags'];
        foreach($tags as $selectedOption){
            echo $selectedOption."\n";
            //Get the id of the selected tag
            $selectedOption = $conn->real_escape_string($selectedOption);
            $sql = "select ID from gametags where Nume ='$selectedOption'";
            $result = $conn->query($sql);
            if($result->num_rows == 0){
                die("Cannot get the tag id from the database!\n");
            }
            $tag_id = $result->fetch_assoc()['ID'];

            //Add the selected options as the tags of the game
            $sql = "insert into gamestag (GameID, TagID) values($game_id, $tag_id)";
            $result = $conn->query($sql);
            //Check if the insertion was succesful
            if($result == false){
                die("Cannot insert the game-tag association into the database!\n");
            }
        }


        $photosUploadDir = $uploaddir . "/Photos";
        $result = mkdir($photosUploadDir, "0777");

        foreach($_FILES['photos']["error"] as $key => $error){
            if ($error == UPLOAD_ERR_OK){
                $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                $photo_name = basename($_FILES["photos"]["name"][$key]);
                if(move_uploaded_file($tmp_name, $photosUploadDir."/".$photo_name)){
                    echo "$photo_name was succesfully uploaded!\n";
                }
                else{
                    echo "$photo_name could not be uploaded!\n"; 
                }

                //Save the photo path into the database
                $database_filepath = $conn->real_escape_string($photosUploadDir."/".$photo_name);
                $sql = "insert into gamephotos (GameID, Path) values($game_id, '$database_filepath')";
            
                $result = $conn->query($sql);
                //Check if the insertion was succesful
                if($result == false){
                    die("Could not insert into the database the photo: $photo_name!\n");
                }
            }
        }

        $videosUploadDir = $uploaddir . "/Videos";
        $result = mkdir($videosUploadDir, "0777");
        
        foreach($_FILES['videos']["error"] as $key => $error){
            if ($error == UPLOAD_ERR_OK){
                $tmp_name = $_FILES["videos"]["tmp_name"][$key];

                $video_name = basename($_FILES["videos"]["name"][$key]);
                if(move_uploaded_file($tmp_name, $videosUploadDir."/".$video_name)){
                    echo "$video_name was succesfully uploaded!\n";
                }
                else{
                    echo "$video_name could not be uploaded!\n"; 
                }

                //Save the video path in the database
                $database_filepath = $conn->real_escape_string($videosUploadDir."/".$video_name);
                $sql = "insert into gamevideos(GameID,Path) values($game_id, '$database_filepath')";
                $result = $conn->query($sql);
                //Check if the video path was saved in the database
                if($result == false){
                    die("Could not insert into the database the video: $video_name!\n");
                }
            }
        }

        //Take the details title and content
        for($i = 1; $i < 6; $i++){
            if(!isset($_POST["title".$i])){
                break;
            }
            $title = $conn->real_escape_string($_POST["title".$i]);
            $content = $conn->real_escape_string($_POST["content".$i]);

            //Save the title and details for the game in the database
            $sql = "insert into gamedetails(GameID, Title, Text) values($game_id, '$title', '$content')";
            $result = $conn->query($sql);   
            if($result == false){
                die("Cannot insert detail: $title, content: $content!\n");
            }
        }

        //Take the minimum system requirments
        $min_os = $conn->real_escape_string($_POST["min_os"]);
        $min_processor = $conn->real_escape_string($_POST["min_processor"]);
        $min_memory = $conn->real_escape_string($_POST["min_memory"]);
        $min_graphics = $conn->real_escape_string($_POST["min_graphics"]);
        $min_directx = $conn->real_escape_string($_POST["min_directx"]);
        $min_storage = $conn->real_escape_string($_POST["min_storage"]);
        $min_additional = $conn->real_escape_string($_POST["min_additional"]);

        //Insert the minimum requirements in the min requirements table
        $sql = "insert into minsysreq(OS, Processor, Memory, Graphics, DirectX, Storage, AdditionalNotes) values('$min_os', '$min_processor', '$min_memory', '$min_graphics', '$min_directx', '$min_storage', '$min_additional')";
        $result = $conn->query($sql);
        if($result == false){
            die("Cannot insert the minimum requirements for the game: $game_name!\n");
        }
        $min_id = $conn->insert_id;

        //Take the recommended system requirements
        $rec_os = $conn->real_escape_string($_POST["rec_os"]);
        $rec_processor = $conn->real_escape_string($_POST["rec_processor"]);
        $rec_memory = $conn->real_escape_string($_POST["rec_memory"]);
        $rec_graphics = $conn->real_escape_string($_POST["rec_graphics"]);
        $rec_directx = $conn->real_escape_string($_POST["rec_directx"]);
        $rec_storage = $conn->real_escape_string($_POST["rec_storage"]);
        $rec_additional = $conn->real_escape_string($_POST["rec_additional"]);
    
        $sql = "insert into recsysreq(OS, Processor, Memory, Graphics, DirectX, Storage, AdditionalNotes) values('$rec_os', '$rec_processor', '$rec_memory', '$rec_graphics', '$rec_directx', '$rec_storage', '$rec_additional')";
        $result = $conn->query($sql);
        if($result == false){
            die("Cannot insert the recommended system requirements for game: $game_name!\n");
        }

        $rec_id = $conn->insert_id;

        //Associate the min,rec system requirements with the game
        $sql = "insert into sysrequirements(GameID, MinSysReq, RecSysReq) values($game_id, $min_id, $rec_id)";
        $result = $conn->query($sql);
        if($result == false){
            die("Cannot insert the system requirements for game: $game_name!\n");
        }


        $conn->close();
    
    }
?>