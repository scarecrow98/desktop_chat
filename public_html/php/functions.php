<?php

    function jsonResponse($response){
        header("Content-Type: application/json", true);
        return json_encode($response);
    }

?>