<!DOCTYPE Html>
<html lang="en">
<head>
    <title>Remove a dish from the menu</title>
    <link rel="stylesheet" type="text/css" href="./styles/menu_management.css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
    require_once 'nav.php';
    require_once 'functions.php';
        if (!IsLogged()) {
            header("location:login.php");
        }
    if($_SESSION['IsAdmin'] == 1){
        echo <<<p
            <div class='add_dish'>
                <form action="menu_remove_dish.php" method="POST" name="remove">
                <h1 class="remove_dish_h1">Remove a dish from the menu</h1>
                <label class="remove_label" for="dish_to_delete">Remove: </label>
                <select name="dish_to_delete" id="dish_to_delete">
                <option value="none" id="none">None</option>
        p;
        // Prende tutti i piatti del menu e crea una option per ognuno di essi
        $dishes = OnlyDish();
        foreach ($dishes as $dish){
            echo "<option value='".$dish['nome_piatto']."' id='".$dish['nome_piatto']."'>".$dish['nome_piatto']."</option> ";
        }
        echo "<input type='submit' name='remove' value='Remove'>";
        echo "</select></form></div>";
        if (isset($_POST['dish_to_delete'])){
            $dish = $_POST['dish_to_delete'];
            // Viene effettuata una query che rimuove il piatto dal menù
            RemoveDish($dish);
            header('location:menu.php');
        }
    }
    else{
        header("location: show_profile.php");
        exit();
    }?>
</body>
</html>