<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Change Profile</title>
    <link rel="stylesheet" type="text/css" href="./styles/update.css">
    <link rel="stylesheet" type="text/css" href="./styles/login.css">
    <link rel="stylesheet" type="text/css" href="./styles/nav.css">
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
?>
    <article>
        <div class="container">
            <!-- Questo form è un template -->
            <div class="title">Change your profile</div>
            <div class="form_content">
                <form action="update_profile.php" method="post">
                    <div class="field">
                        <input required type="text" name="firstname" placeholder="Your new name" value="<?php echo $_SESSION['firstname']?>">
                        <label>New name</label>
                    </div>
                    <div class="field">
                        <input required type="text" name="lastname" placeholder="Your new lastname" value="<?php echo $_SESSION['lastname']?>">
                        <label>New lastname</label>
                    </div>
                    <div class="field">
                        <input required type="email" name="email" placeholder="Your email" value="<?php echo $_SESSION['email']?>">
                        <label>New email</label>
                    </div>
                    <div class="field">
                        <input type="submit" name="submit" value="Update">
                    </div class="field">
                </form>
            </div>
        </div>
    </article>
</body>
<?php
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (strlen($_POST['firstname']) != 0 && strlen($_POST['lastname']) != 0 && strlen($_POST['email']) != 0) {
            if ($_SESSION['firstname'] != $_POST['firstname'] || $_SESSION['lastname'] != $_POST['lastname'] || $_SESSION['email'] != $_POST['email']) {
                Update_Profile($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_SESSION['email']);
            }
            header('location: show_profile.php');
        }
    }
}
catch (\Throwable){
    echo <<<p
        <article><h2>Oh no! An error occurred. Try again later</h2></article>;
        p;
    exit();
}
?>
</html>