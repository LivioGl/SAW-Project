<script src="./scripts/cart.js" type="application/javascript"></script>
<?php
session_start();
require_once "functions.php";
// Elimina le variabili di sessione
if(isset($_COOKIE['RememberMe'])){
    DeleteCookie($_COOKIE['RememberMe']);
    setcookie("RememberMe","",time()-3600);
}
$_SESSION = array();
?>
    <script>
        sessionStorage.clear();
    </script>
<?php
session_destroy();
header('location:index.php');
exit();
