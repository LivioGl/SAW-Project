<?php
session_start();
require_once 'functions.php';
if(IsLogged() && ($_SESSION['IsAdmin'] == 1)){
    try{
        $con = Con();
        if($_POST['admin'] == 1)
            $a = 0;
        else
            $a = 1;
        $query = "UPDATE users_info SET IsAdmin = " . $a ." WHERE email like '".$_POST['email']."';";
        $con->query($query);
        header('location:admin.php');
    }
    catch(\Throwable){
        return false;
    }
}
else{
    header("location: show_profile.php");
    exit();
}