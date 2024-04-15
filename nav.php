<?php
session_start();
require_once "functions.php"; ?>
    <header>
        <h1>Osteria da Plinio</h1>
    </header>
<?php
if(!IsLogged()) {
// Contenuto per utenti non loggati
    echo <<<Unlog
        <nav>
            <a href = "index.php" > Home</a>
            <a href = "menu.php" > Menu</a >
            <a href = "underconstruction.php" > The restaurant </a >
            <a href = "underconstruction.php" > Contact us</a >
            <a href = "login.php" class="right">Login</a>
        </nav>
    Unlog;
}
else{
// Contenuto per utenti loggati
    echo <<<Log
        <nav>
            <a href = "index.php"> Home</a >
            <a href = "menu.php"> Menu</a >
            <a href = "underconstruction.php" > The restaurant </a >
            <a href = "underconstruction.php" > Contact us</a >
            <a href = "logout.php" class="right" onclick="sessionStorage.clear()">Log out</a>
            <a href = "show_profile.php" class="right" >Your profile</a >
            <a href = "cart.php" class="right" >Cart</a >
        </nav>  
    Log;
}
?>
