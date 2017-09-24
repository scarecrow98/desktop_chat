<?php

    include("functions.php");

    if( isset($_POST["image"]) ){
        $base64 = $_POST["image"];

        //nyers formátum: image/png;base64,dFS43_asd...
        $base64_image = explode("," , $base64)[1]; //nyerformátumot szétválasztja, hogy megkapja a kép tiszta kódját
        $image = base64_decode($base64_image);

        $image_name = md5(mt_rand(100,999)) . ".png";
        $image_path = "../img/uploaded_images/" . $image_name;

        file_put_contents($image_path, $image);

        $response = array( "imageName" => $image_name );

        echo jsonResponse($response);
    }

?>