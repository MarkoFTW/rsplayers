<?php

    try{
        $mysqli = new PDO("mysql:host=localhost;dbname=DB", "USER", "PASSWORD");
    } catch (Exception $ex) {
        die("Error: " . $ex->getMessage());
    }


?>