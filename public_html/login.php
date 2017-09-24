<?php

    session_start();
    include("php/db-con.php");

    if( isset($_POST["btn-login"]) ){
        $username = addslashes( strip_tags( htmlspecialchars( $_POST["input-username"] ) ) );
        $password = addslashes( strip_tags( htmlspecialchars( $_POST["input-password"] ) ) );

        if( $username == "" || $password == "" ){
            echo '<div class="error-message"> Nem adtál meg felhasználónevet vagy jelszót! </div>';
        }
        else{
            $query = "SELECT * FROM users WHERE username = '". $username ."' AND password = '". md5($password) ."'";
            $result = mysqli_query($connect, $query) or die("Nem sikerült végrehajtani a parancsot az adatbázisban!");

            if( mysqli_num_rows($result) == 1 ){
                $user_data = mysqli_fetch_assoc($result);
                $_SESSION["username"] = $user_data["username"];
                $_SESSION["fullname"] = $user_data["fullname"];
                $_SESSION["profpic"] = $user_data["avatar"];

                $query = "UPDATE users SET online = 1 WHERE username = '" . $user_data["username"] . "'";
                mysqli_query($connect, $query) or die(mysqli_error($connect));
                

                header("Location: index.php");
            }
            else{
                echo '<div class="error-message"> Felhasználónév vagy jelszó nem megfelelő! </div>';
            }
        }

    } 

?>

<html>
    <head>
        <title>Bejelentkezés</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="css/login.css">
        <script src="js/jquery.js"></script>
    </head>
    <body>
        
        <div id="login-panel">
            <form method="post" action="<?php $_SERVER["PHP_SELF"] ?>" id="login-form">

                <div class="input-container">
                    <label for="input-username">Felhasználónév</label>
                    <input type="text" id="input-username" name="input-username" autocomplete="off">
                </div>

                <div class="input-container">
                    <label for="input-password">Jelszó</label>
                    <input type="password" id="input-password" name="input-password">
                </div>

                <div class="input-container">
                    <button id="btn-login" class="btn" name="btn-login">Belépés</button>
                    <button id="btn-reg" class="btn">Regisztráció</button>
                </div>

            </form>
        </div>

    </body>
</html>

<script>

    $("#btn-reg").click(function(e){
        e.preventDefault();
        window.location = "registration.php";
    });

</script>