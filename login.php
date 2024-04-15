<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Login to your profile</title>
    <link rel="stylesheet" type="text/css" href="./styles/login.css">
    <link rel="stylesheet" type="text/css" href="./styles/nav.css">
    <link rel="stylesheet" type="text/css" href= "./styles/home.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
        require_once "nav.php";
        require_once "server_settings.php";
    ?>
        <article>
            <div class="container">
                <div class="title">
                    Login to your profile
                </div>
                <div class="form_content">
                    <form action="login.php" method="post">
                        <div class="field">
                            <input type="email" name="email" placeholder=" Insert your email ">
                            <label>Email</label>
                        </div>
                        <div class="field">
                            <input type="password" name="pass" placeholder=" Insert your password ">
                            <label>Password</label>
                        </div>
                        <input type="checkbox" name="RememberMe" id="RememberMe" value="true">
                        <label>Remember me</label>

                        <div class="field">
                            <input type="submit" name="submit" class="button_login" value=" Login ">
                        </div>
                    </form>
                        <p id="new_user_p">New user? Sign up <a href="registration.php" id="new_user">Here</a></p>
                </div>
            </div>
        </article>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['pass'];
        if( !(strlen($email) && strlen($password))){
            ?>
            <article>
                <?php echo "<p class = 'error-msg'>There are empty fields, check what you typed.\n";
                exit(); ?>
            </article>
            <?php
        }
        //
        $row = FetchFromDB($email);
        if(!$row){
            echo <<<p
                <article><h1 class="error-msg">Email or password not found</h1></article>
            p;
            exit();
        }
        // Per verificare la password devo usare la seguente funzione
        if (!(password_verify($password, $row['password']))){
            echo <<<p
                <article><h1 class="error-msg">Email or password not correct</h1></article>
            p;
           exit();
        }
        if ($_POST['RememberMe'] === "true") {
            RememberMe($email);
        }
        // Inizializzo le variabili di sessione con gli attributi della tabella
        $_SESSION["Id"] = $row['Id'];
        $_SESSION["firstname"] = $row['nome'];
        $_SESSION["lastname"] = $row['cognome'];
        $_SESSION["email"] = $row['email'];
        $_SESSION['IsAdmin'] = $row['IsAdmin'];
        $_SESSION['Id'] = $row['Id'];
        $_SESSION['IsBanned'] = $row['IsBanned'];
        header("location: index.php");
    }?>
</body></html>