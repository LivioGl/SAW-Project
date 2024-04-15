<!DOCTYPE Html>
<html lang="en">
<head>
    <title>Admin area</title>
    <link rel="stylesheet" type="text/css" href="./styles/admin.css">
    <link rel="stylesheet" type="text/css" href= "./styles/nav.css">
    <link rel="stylesheet" type="text/css" href= "./styles/home.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
        <?php
            require_once 'nav.php';
            require_once 'functions.php';
            if (!IsLogged()){
                header("location:login.php");
            }
            if($_SESSION['IsAdmin'] == 1){
                $users = UsersList();
                echo "<table class='user-list'>
                         <tr>
                             <th>Nome</th>
                             <th>Cognome</th>         
                             <th>Email</th>
                             <th colspan='3'>Commands</th>
                         </tr>";
                foreach ($users as $user){
                    echo "<tr>
                        <td>".$user['nome']."</td>
                        <td>".$user['cognome']."</td>
                        <td>".$user['email']."</td>";
                    echo "<td>";
                    BanButton($user);
                    AppointAdmin($user);
                    DeleteButton($user);
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            else{
                header("location:show_profile.php");
                exit();
            }
        ?>
</body>
</html>