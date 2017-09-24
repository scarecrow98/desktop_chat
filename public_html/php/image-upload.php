<?php

    if( $_FILES["image"]["name"] != "" || isset($_FILES["image"]) ){
        $allowed_extensions = array( "image/jpeg" => "jpg", "image/png" => "png", "image/gif" => "gif", "image/png" => "PNG");
        $max_img_size = 10 * 1024 * 1024;

        $img_tmp = $_FILES["image"]["tmp_name"]; //feltöltött fájl átmeneti mappája
        $img_size = $_FILES["image"]["size"]; //fájl mérete
        $img_pathinfo = pathinfo($_FILES["image"]["name"]); //3 értéket ad vissza -> extension, dirname, basename
        $img_extension = $img_pathinfo["extension"]; //fájl kiterjsztése
        $img_dimensions = getimagesize($img_tmp); //3 értéket ad vissza -> szélesség, magasság, mime típus
        $img_mime_type = $img_dimensions["mime"]; //fájl mime típusa

        if( is_uploaded_file($img_tmp) ){ //ha feltöltöttek fájlt, akkor...
            if($img_size > $max_img_size){ //ha a fájlméret naagyobb, mint 10MB
                $response = array("result" => "A fájl mérete nem lehet nagyobb, mint 10MB!");
            }
            else if( !in_array($img_extension, $allowed_extensions) && !isset($allowed_extensions[$img_mime_type]) ){ //ha megfelelő a kiterjesztés és a mime típus is
                $response = array("result" => "Csak PNG, JPG és GIF fájlok tölthetőek fel a szerverre!");
            }
            else{
                $uploaded_img_name = md5(mt_rand(100,999)) . "." . $img_extension;
                move_uploaded_file($img_tmp, "../img/uploaded_images/".$uploaded_img_name);
                $response = array("result" => "success", "imageName" => $uploaded_img_name);
            }
        }
        else{ //..egyébként hibaüzenet
            $response = array("result" => "Hiba történt a képeltöltés közben!");
        }
            
        header("Content-Type: application/json", true);
        echo json_encode($response);
    }

?>