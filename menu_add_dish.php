<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Add a dish to the menu</title>
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
            <form action="menu_add_dish.php" method="POST" name="dish">
                <table>
                    <tr rowspan="1" span="1"><h2 class="query-add">New menu dish</h2></tr>
                    <tr>
                        <td><label>New dish name: </label></td>                   
                        <td><input type="text" name="newdish" placeholder="New dish"></td>
                    </tr>
                    <tr>
                        <td><label>Dish type: </label></td>  
                        <td><select name="type">
                                <option value="primo">Primo</option>
                                <option value="secondo">Secondo</option>
                                <option value="pizza">Pizza</option>
                            </select></td>
                    </tr>    
                    <tr>
                        <td><label>New dish img: </label></td>
                        <td><input type="url" name="imglink" placeholder="Img url"></td>
                    </tr>
                    <tr>
                        <td><label>Price: </label></td>
                        <td><input type="number" name="price" placeholder="Price"></td>    
                    </tr>
                    <tr>
                        <td><label>Allergen: </label></td>
                        <td rowspan="3" class="checkbox-row">
                            <label for="gluten">Gluten: </label>
                            <input type="checkbox" name="gluten" id="gluten">
                            <label for="lactose">Lactose: </label>
                            <input type="checkbox" name="lactose" id="lactose">
                            <label for="egg">Egg: </label>
                            <input type="checkbox" name="egg" id="egg">
                        </td>
                    </tr>           
                </table>
                <input type="submit" name="submit" value="Add">
            </form>
        </div>
        p;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dish_name = htmlspecialchars($_POST["newdish"]);
            $img = htmlspecialchars($_POST["imglink"]);
            $price = $_POST["price"];
            if(!(strlen($dish_name) && strlen($img) && strlen($price)))
            {
                ?>
                <article>
                    <?php echo "<p class = 'error-msg'>There are empty fields, check what you typed.\n";
                    exit();
                    ?>
                </article>
                <?php
            }
            AddToMenu();
            header('location:menu.php');
        }
    }
    else{
        header("location: show_profile.php");
        exit();
    }
     ?>
</body>
</html>