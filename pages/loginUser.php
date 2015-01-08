<?php
    session_start();
    include 'class.php';
    include 'baza.inc.php';

    if(isset($_POST['UserMailLogin']) && isset($_POST['UserPasswordLogin'])) {
        $user = new user();
        $user->setEmail($_POST['UserMailLogin']);
        $user->setPassword(sha1($_POST['UserPasswordLogin']));
        $user->LoginUser();

        if(isset($_POST['ChkBox']) && $_POST['ChkBox'] == "yes"){
            setcookie("userid", $user->getUserID(), time()+86400, '/');
            setcookie("username", $user->getUsername(), time()+86400, '/');
            setcookie("email", $user->getEmail(), time()+86400, '/');
        }
        
        if($user->LoginUser()){
            echo "true";
        } else {
            echo "false";  
        }
    }else if(isset($_POST['UserNameLogin']) && isset($_POST['UserPasswordLogin'])) {
        $user = new user();
        $user->setUsername($_POST['UserNameLogin']);
        $user->setPassword(sha1($_POST['UserPasswordLogin']));
        $user->LoginUserName();

        if(isset($_POST['ChkBox']) && $_POST['ChkBox'] == "yes"){
            setcookie("userid", $user->getUserID(), time()+86400, '/');
            setcookie("username", $user->getUsername(), time()+86400, '/');
            setcookie("email", $user->getEmail(), time()+86400, '/');
        }
        if($user->LoginUserName()){
            echo "true";
        } else {
            echo "false";  
        }
    } else {
        header("Location: ../index.php?");
    }
    
?>