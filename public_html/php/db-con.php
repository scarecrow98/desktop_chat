<?php

    define("host_name", "localhost");
    define("db_user", "root");
    define("db_password", "");
    define("db_name", "webchat");

    function newDatabaseConnection(){
        $db = new PDO("mysql:host=".host_name.";dbname=".db_name, db_user, db_password);
        $db->setAtrribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

?>