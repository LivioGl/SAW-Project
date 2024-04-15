<?php
require_once 'functions.php';
session_start();
if(IsLogged() && $_SESSION['IsAdmin'] == 1){
    try{
        $con = Con();
        $query = "DELETE FROM users_info WHERE Id LIKE '".$_POST['Id']."'; ";
        $con->query($query);
        header('location:admin.php');
    }
    catch(\Throwable ){
        return false;
    }
}
else{
    header("location: show_profile.php");
    exit();
}
