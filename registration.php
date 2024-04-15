<!DOCTYPE Html>
<html lang="it">
<head>
    <link rel="stylesheet" href="./styles/form.css" type="text/css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
        require_once "functions.php";
        require_once "nav.php";
    ?>
    <article>
    <!-- Il form di registrazione è un template -->
        <div class="container">
            <div class="title">Registration form</div>
            <div class="form_content">
                <form action="registration.php" method="post">
                    <div class="field">
                        <input type="text" name="firstname" placeholder="Your name">
                        <label>Firstname</label>
                    </div>
                    <div class="field">
                        <input type="text" name="lastname" placeholder="Your lastname">
                        <label>Lastname</label>
                    </div>
                    <div class="field">
                        <input type="email" name="email" placeholder="Your email">
                        <label>Email</label>
                    </div>
                    <div class="field">
                        <input type="password" name="pass" placeholder="Your password">
                        <label>Password</label>
                    </div>
                    <div class="field">
                        <input type="password" name="confirm" placeholder="Repeat your password">
                        <label>Confirm password</label>
                    </div>
                    <div class="field">
                        <input type="submit" name="submit" value="Register">
                    </div class="field">
                </form>
            </div>
        </div>
    </article>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lettura dei dati con filtro per sanificare le email
        $nome = htmlspecialchars($_POST['firstname']);
        $cognome = htmlspecialchars($_POST['lastname']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['pass'];
        $confirmpw = $_POST['confirm'];
        // Controlli per evitare campi vuoti
        if(!(strlen($nome) && strlen($cognome) && strlen($email) && strlen($password) && strlen($confirmpw))){
        ?>
            <article>
                <?php echo "<p class = 'error-msg'>There are empty fields, check what you typed.\n";
                    exit();
                ?>
            </article>
            <?php
        }
        // Controlli sulla password: deve essere minimo 7 caratteri di lunghezza
        if (strlen($password) <= 6){
            ?>
            <article>
                <?php echo "<p class = 'error-msg'>Password too short. Use at least 7 characters.\n";
                exit(); ?>
            </article>
            <?php
        }
        // Controllo password = conferma password
        if ($password != $confirmpw){
            ?>
            <article>
                <?php echo "<p class = 'error-msg'>Passwords do not match.\n";
                exit(); ?>
            </article>
            <?php
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        AddToDB($nome, $cognome, $email, $password);
        header("location:login.php");
    }
    ?>
</body>
</html>