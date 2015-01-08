<?php
    session_start();
    include 'class.php';
    include 'baza.inc.php';
    
    if(isset($_POST['submitlogout'])){
        setcookie("userid", $_SESSION['UserID'], time()-86400, '/');
        setcookie("username", $_SESSION['Username'], time()-86400, '/');
        setcookie("email", $_SESSION['Email'], time()-86400, '/');
        $_SESSION = array();
        session_destroy();
        header("Location: ../index.php");
    } else {
        header("Location: ../index.php");
    }
?>