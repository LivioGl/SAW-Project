<?php
session_start();
require_once 'functions.php';
if(IsLogged() && $_SESSION['IsAdmin'] == 1){
    try{
        $con = Con();
        // Assegna il valore opposto di IsBanned a $a
        if($_POST["ban"] == 1)
            $a = 0;
        else
            $a = 1;
        $query = "UPDATE users_info SET IsBanned = " . $a . " WHERE email LIKE '".$_POST['email']."';";
        $con->query($query);
        header('location:admin.php');
    }
    catch(\Throwable) {
        return false;
    }
}
else{
    header("location: show_profile.php");
    exit();
}

