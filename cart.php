<!DOCTYPE Html>
<html lang="it">
<head>
    <link rel="stylesheet" type="text/css" href="./styles/cart.css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <script src="https://kit.fontawesome.com/4d9e73afea.js" crossorigin="anonymous"></script>
    <script src="./scripts/cart.js" type="application/javascript"></script>
    <meta charset="UTF-8">
    <title>Your cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
    require_once "nav.php";
    require_once "server_settings.php";
    require_once "functions.php";
if (!IsLogged()) {
    header("location:login.php");
}
if(!isset($_POST['carrello']))
{
    echo "<script>manda();</script>";
    exit();
}
    $con= Con();
    // Decodifica l'oggetto Json (il carrello) e lo converte in un oggetto PHP
    $cart = json_decode($_POST['carrello'],true );
    // Tolgo gli spazi vuoti prima e dopo una parola
    $x = "";
    foreach($cart['keys'] as $key) {
        $x.= "'".$con->real_escape_string($key)."', ";
    }
    $x=rtrim($x,", ");
    $a = 1;
    //Query per prendere gli elementi
    try{
        $result = $con->query("SELECT * FROM menu WHERE nome_piatto IN (".$x.") ORDER BY nome_piatto");
    }
    catch(\Throwable $a){
        $a = 0;
    }
    if($a == 0){
        echo "<h2> Il tuo carrello è vuoto</h2>";
    }
    else{
    ?>
<body>
        <h2>Your cart</h2>
        <table class="mycart">
            <tr>
                <th> </th>
                <th>Dish</th>
                <th>Price</th>
                <th>Amount</th>
                <th> </th>
            </tr>
            <?php
            $i = 0;
            // Prendo gli elementi del carrello e li stampo a video
            while($piatto = $result->fetch_assoc()){
            ?>
            <tr>
                <td><img src="<?php echo $piatto["img"]; ?>" alt="<?php echo $piatto["nome_piatto"];?>"></td>
                <td> <?php echo $piatto["nome_piatto"]; ?></td>
                <td> € <?php echo $piatto["costo"]; ?>.00</td>
                <td><?php echo $cart["values"][$i]; ?></td>
                <td><button type="submit" class="btn-primary" name="remove_to_cart" onclick='remove_item("<?php echo $piatto["nome_piatto"]; ?>")'>Delete</button></td>
            </tr>
        <?php $i++; }?>
    </table>
    <div class="confirm_order">
        <form action="conferma_ordine.php" name="conferma" id="conferma" method="POST">
            <button type="submit" class="btn-primary" name="ordina_piatti">Order and (not) pay</button>
            <input type="hidden" name="carrello" id="carrello" value='<?php echo $_POST["carrello"]?>'>
        </form>
    </div>
    <?php }
        $orderlist = OrderList($_SESSION['Id']);
    ?>
    <h2>History</h2>
        <table class='mycart'>
            <tr>
                <th>Dish</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
    <?php
            foreach ($orderlist as $order){
                echo "<tr>
                    <td>".$order['nome_piatto']."</td>
                    <td>".$order['quantita']."</td>
                    <td>".$order['data_acquisto']."</td>";
                echo "</tr>";
            }
            echo "</table>";
    ?>
</body>
</html>