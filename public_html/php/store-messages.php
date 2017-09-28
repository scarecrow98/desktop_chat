<?php
    if( isset($_POST["message"]) ){
        try{
            session_start();
            include("db-con.php");

            $sender = $_SESSION["id"];
            $date = date("Y.m.d H:i");
            $text = $_POST["text"];
            $sticker = $_POST["sticker"];
            $image = $_POST["image"];
            echo $image;
            $db = newDatabaseConnection();
            $prepare = $db->prepare("INSERT INTO messages(msg_sender, msg_text, msg_time, msg_image, msg_sticker) VALUES(:sender, :text, :time, :image, :sticker)");
            $prepare->bindParam(":sender", $sender);
            $prepare->bindParam(":text", $text);
            $prepare->bindParam(":time", $date);
            $prepare->bindParam(":image", $image);
            $prepare->bindParam(":sticker", $sticker);
            $prepare->execute();

            if( $prepare->rowCount() == 1 ){
                echo "message saved";
            }
            else{
                echo "failed to save message";
            }
            $db = null;
        }
        catch(PDOException $e){
            echo $e->getMessage();
            die();
        }
    }

?>
