<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Choose an action</title>
    <link rel = "stylesheet" type="text/css" href="./styles/menu_options.css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <script src="https://kit.fontawesome.com/4d9e73afea.js" crossorigin="anonymous"></script>
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
    if($_SESSION['IsAdmin'] == 1){?>
        <div class="add_or_remove">
            <!-- Va alla pagina per aggiungere un piatto al menù -->
            <div class="element">
                <i class="fa-solid fa-plus"></i>
                <a href="menu_add_dish.php">Add a dish to the menu</a>
            </div>
            <!-- Va alla pagina per rimuovere un piatto al menù -->
            <div class="element">
                <i class="fa-solid fa-trash"></i>
                <a href="menu_remove_dish.php">Remove a dish from the menu</a>
            </div>
        </div>
        <?php
    }
    else{
        header("location: show_profile.php");
        exit();
    }
    ?>

</body>
</html>