<?php
require_once 'functions.php';
session_start();
if(IsLogged() && HaAcquistato($_SESSION['Id'], $_POST['piatto'])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        SalvaValutazione($_SESSION['Id'], $_POST['piatto'], $_POST['voto']);
        header("location:menu.php");
    }
}
else{
    header("location:login.php");
    exit();
}

