<?php
    include("functions.php");
    session_start();
    $response = array(
        "username" => $_SESSION["username"],
        "fullname" => $_SESSION["fullname"],
        "profpic" => $_SESSION["profpic"]
    );

    echo jsonResponse($response);

    
?>