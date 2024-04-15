<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Your profile</title>
    <link rel = "stylesheet" type="text/css" href="./styles/profile.css">
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
    else{
        echo "<p class='profile_name'>This is your profile, <b>".$_SESSION['firstname']."</b></p>";
        ?>
    <table>
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
        </tr>
        <tr>
            <th><?php echo"<p>".$_SESSION['firstname']."</p>" ?></th>
            <th><?php echo"<p>".$_SESSION['lastname']."</p>" ?></th>
            <th><?php echo"<p>".$_SESSION['email']."</p>" ?></th>
        </tr>
        </table>
    <?php
        echo <<<p
                    <div class="change_profile"><button><a href='update_profile.php'>Update your profile</a></button>
                    p;
        if($_SESSION["IsAdmin"] == 1){
               echo <<<p
                    <button><a href='admin.php'>User administration</a></button>
                    <button class="menu"><a href="menu_options.php" >Menu administration</a></button>
               p;
        }
        echo "</div>";
    }
?>
</body>
</html>