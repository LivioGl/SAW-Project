<script src="./scripts/cart.js" type="application/javascript"></script>
<?php
require_once "functions.php";
$con = Con();
session_start();
    if (!IsLogged()){
        header ("location:login.php");
    }
    if(!isset($_POST['carrello'])) {
        header("location:cart.php");
        exit();
    }
    // Decodifica l'oggetto JSON in un oggetto PHP e poi inserisce nello storico ciò che abbiamo appena ordinato
    $cart = json_decode($_POST['carrello'],true );

    $errore = 0;
    try {
        $stmt = $con->prepare("INSERT INTO storico_acquisti ( id_utente, nome_piatto, quantita) VALUES (" . $_SESSION['Id'] . ", ?, ?)");
        for ($i = 0; $i < count($cart['keys']); $i++) {
            $stmt->bind_param('ss', $cart['keys'][$i], $cart['values'][$i]);
            $stmt->execute();
        }
    } catch (\Throwable $e) {
        // Errore durante l'acquisto
    }
    ?>
    <script><?php
        // Rimuove ogni cosa nel carrello dopo che l'utente ha effettuato l'ordine
        foreach($cart['keys'] as $key) {
            echo "remove_item('$key'); ";
        }
        ?>
    </script>