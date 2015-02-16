<?php

    include 'class.php';
    if(isset($_POST['Username'], $_POST['Email'], $_POST['Password'])){
        
        $regnow = new user();
        $regnow->setEmail($_POST['Email']);
        $regnow->setPassword(sha1($_POST['Password']));
        $regnow->setUsername($_POST['Username']);
        $regnow->setIP($_SERVER['REMOTE_ADDR']);
        $regnow->Register();
        
        if($regnow->Register() == "true"){
            echo "true";
        } else {
            echo "false";
        }
    }
    
?>