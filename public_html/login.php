<?php

    session_start();
    include("php/db-con.php");

    if( isset($_POST["btn-login"]) ){
        $username = $_POST["input-username"];
        $password = $_POST["input-password"];
        $password_hash = hash('sha256', $password);
        $regex_username = "/^[a-zA-Z0-9._-]{5,30}$/";

        if( $password == "" || $username == ""){
            echo "Felhasználónév vagy jelszó nincs megadva!";
        }
        else{
            try{
                $db = newDatabaseConnection();
                $prepared = $db->prepare("SELECT * FROM users WHERE user_name = :username AND user_password = :password");
                $prepared->bindParam(":username", $username);
                $prepared->bindParam(":password", $password_hash);
                $prepared->execute();
                $result = $prepared->fetch(PDO::FETCH_ASSOC);

                if( $prepared->rowCount() == 1 ){
                    $_SESSION["username"] = $result["user_name"];
                    $_SESSION["password"] = $result["user_password"];
                    $_SESSION["id"] = $result["user_id"];
                    $_SESSION["avatar"] = $result["user_avatar"];
                    $_SESSION["first"] = $result["user_first"];
                    $_SESSION["last"] = $result["user_last"];
                    $_SESSION["email"] = $result["user_email"];
                    header("Location: index.php?login=success");
                }
                else{
                    echo "Felhasználói adatok helytelenek!";
                }
            }
            catch(PDOException $e){
                echo "Hiba: " . $e->getMessage();
                die();
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