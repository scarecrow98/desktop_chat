<?php
    header("Content-Type: text/html; charset=UTF-8");

    if( isset($_POST["btn-reg"]) ){
        $fullname = strip_tags( htmlspecialchars( $_POST["input-fullname"] ) );
        $email = $_POST["input-email"];
        $username = strip_tags( htmlspecialchars( $_POST["input-username"] ) );
        $password = $_POST["input-password"];
        $avatar = $_FILES["input-avatar"];

        $regex_fullname = "/[a-zA-Z]/";
        $regex_username = "/[a-zA-Z0-9]{5,30}/";
        $regex_password = "/[a-zA-Z0-9]{8,255}/";

        $fail = false;

        if( !preg_match($regex_fullname, $fullname) ){
            $fail = true;
            echo '<div class="error-message">A teljes név nem felel meg a feltételeknek!</div>';
        }
        else if( !preg_match($regex_username, $username) ){
            $fail = true;
            echo '<div class="error-message">A felhasználónév nem felel meg a feltételeknek!</div>';
        }
        else if( !preg_match($regex_password, $password) ){
            $fail = true;
            echo '<div class="error-message">A jelszó nem felel meg a feltételeknek!</div>';
        }

        if( is_uploaded_file($avatar["tmp_name"]) ){
            $allowed_extensions = array( "image/jpeg" => "jpg", "image/png" => "png", "image/gif" => "gif", "image/png" => "PNG");
            $max_img_size = 5 * 1024 * 1024;
    
            $img_tmp = $avatar["tmp_name"]; //feltöltött fájl átmeneti mappája
            $img_size = $avatar["size"]; //fájl mérete
            $img_pathinfo = pathinfo($avatar["name"]); //3 értéket ad vissza -> extension, dirname, basename
            $img_extension = $img_pathinfo["extension"]; //fájl kiterjsztése
            $img_dimensions = getimagesize($img_tmp); //3 értéket ad vissza -> szélesség, magasság, mime típus
            $img_mime_type = $img_dimensions["mime"]; //fájl mime típusa

            if($img_size > $max_img_size){ //ha a fájlméret naagyobb, mint 10MB
                echo '<div class="error-message">A fájl mérete nem lehet nagyobb, mint 10MB!</div>';
            }
            else if( !in_array($img_extension, $allowed_extensions) && !isset($allowed_extensions[$img_mime_type]) ){ //ha megfelelő a kiterjesztés és a mime típus is
                echo '<div class="error-message">Csak PNG, JPG és GIF fájlok tölthetőek fel a szerverre!</div>';                
            }
            else{
                $uploaded_avatar_name = $username . "." . $img_extension;
                move_uploaded_file($img_tmp, "img/avatars/".$uploaded_avatar_name);
            }
        }
        else{
            $fail = true;
            echo '<div class="error-message">Nem lett profilkép felöltve!</div>';            
        }

        if( !$fail ){
            include("php/db-con.php");
            $password_hash = md5($password);

            $query = "INSERT INTO users(fullname, username, password, email, avatar) VALUES('$fullname', '$username', '$password_hash', '$email', '$uploaded_avatar_name' )";
            $result = mysqli_query($connect, $query) or die("Nem sikerült végrehajtani az adatbázis műveletet!".mysqli_error($connect));
            
            if($result){
                session_start();
                $_SESSION["username"] = $username;
                $_SESSION["fullname"] = $fullname;
                $_SESSION["profpic"] = $uploaded_avatar_name;
                header("Location: index.php");
            }
        
        }
    } 

?>
<html>
    <head>
        <title>Regisztráció</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/ionicons.min.css"/>
        <script src="js/jquery.js"></script>
    </head>
    <body>  

        <div id="registration-panel">
                <form method="post" action="" id="registration-form" enctype="multipart/form-data">
                   
                    <div class="input-container">
                        <label for="input-fullname">Teljes név</label>
                        <input type="text" id="input-fullname" name="input-fullname" autocomplete="off" >
                        <small>Csak kis- és nagybetűk</small>
                    </div>

                    <div class="input-container">
                        <label for="input-email">Email cím</label>
                        <input type="email" id="input-email" name="input-email" autocomplete="off" >
                    </div>

                    <div class="input-container">
                        <label for="input-username">Felhasználónév</label>
                        <input type="text" id="input-username" name="input-username" autocomplete="off" >
                        <small>Kis- és nagybetűk, 5-30 karakter, pont, aláhúzás- és kötőjel</small>
                    </div>
    
                    <div class="input-container">
                        <label for="input-password">Jelszó</label>
                        <input type="password" id="input-password" name="input-password" >
                        <small>8-255 karakter</small>
                    </div>

                    <div class="input-container">
                        <label>Profilkép kiválasztása</label>
                        <input type="file" id="input-avatar" name="input-avatar" style="display: none;" >
                        <button id="btn-avatar" class="btn" name="input-avatar"><i class="ion-ios-upload-outline"></i></button>
                        <small>PNG, JPG, GIF képek, max. 5MB</small>
                    </div>
    
                    <div class="input-container">
                        <button id="btn-reg" class="btn" name="btn-reg">Regisztráció</button>
                    </div>
    
                </form>
            </div>

    </body>
</html>

<script>

    $("#btn-avatar").click(function(e){
        e.preventDefault();
        $("#input-avatar").click();
    });

</script>