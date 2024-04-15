<?php
    // Function which gets all dishes from the menu
    function ListaPiatti()
    {
        try{
            $con = Con();
            $result = $con->query("SELECT menu.nome_piatto, costo, img, tipo_piatto, glutine, uova, lattosio 
                FROM menu INNER JOIN allergens ON menu.nome_piatto = allergens.nome_piatto ORDER BY tipo_piatto, menu.nome_piatto");
            return $result;
        }
        catch(\Throwable){
            return false;
        }
    }
    // Function for database connection
    function Con()
    {
        require_once "server_settings.php";
        static $con = null;
        if (!$con)
            $con =  mysqli_connect(serverIp, serverUser, password, serverName);
        if (mysqli_connect_errno()) {
            // messaggio per il developer
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            // oppure messaggio per l’utente
            echo "Something went wrong, visit us again later";
        }
        return $con;
    }
    // Function which counts dishes type
    function CountPiatti(){
        try{
            $con = Con();
            $result = $con->query("SELECT tipo_piatto, COUNT(nome_piatto) AS amount FROM menu GROUP BY tipo_piatto ORDER BY tipo_piatto");
            return $result;
        }
        catch(\Throwable){
            return NULL;
        }

    }

    // Function which displays an icon for every allergen
    function Allergens($piatto){
        if ($piatto["glutine"]){
            echo "<i id='gluten' class='fa-solid fa-wheat-awn'></i>  ";
        }
        if($piatto["lattosio"]){
            echo "<i id='lactose' class='fa-solid fa-cow'></i>  ";
        }
        if($piatto["uova"]){
            echo "<i id='egg' class='fa-solid fa-egg'></i>";
        }
        if($piatto["glutine"] == 0 && $piatto["lattosio"] == 0 && $piatto["uova"] == 0){
            echo "No allergens";
        }
    }


// Per controllare se l'utente è loggato e assegna le variabili di sessione
    function IsLogged()
    {
        if (isset($_SESSION['email']))
            return true;
        if (isset($_COOKIE['RememberMe'])) {
            if ($risultato = IsInByToken($_COOKIE['RememberMe'])) {
                $_SESSION['email'] = $risultato['email'];
                $_SESSION['firstname'] = $risultato["nome"];
                $_SESSION['lastname'] = $risultato["cognome"];
                $_SESSION['IsAdmin'] = $risultato['IsAdmin'];
                $_SESSION['Id'] = $risultato['Id'];
                $_SESSION['IsBanned'] = $risultato['IsBanned'];
                return true;
            }
        }
        return false;
    }
// Funzione per il remember me
    function RememberMe($email)
    {
        $con = Con();
        $token = bin2hex(random_bytes(64));
        $scadenza = time() + 86400;
        $scadenza_stringa = date('Y/m/d H:i:s', $scadenza);
        $stmt = $con->prepare("UPDATE users_info SET Token=?, Scadenza=? WHERE email=?");
        $stmt->bind_param("sss", $token, $scadenza_stringa, $email);
        if(!($stmt->execute())){
            return false;
        }
        echo "Accesso eseguito al DB e query eseguita";
        setcookie("RememberMe", $token, $scadenza);
    }

    // Per controllare se un utente risulta già registrato con la mail al DB
    function FetchFromDB($email){
        $con = Con();
        $stmt = $con->prepare("SELECT nome, cognome, email, password, IsAdmin, Id, IsBanned FROM users_info WHERE Email LIKE ?");
        $stmt->bind_param('s',$_POST['email']);
        if(!($stmt->execute())){
            return false;
        }
        $email_list = $stmt->get_result();
        return $email_list->fetch_assoc();
    }

    function AddToDB($nome, $cognome, $email, $password){
        $con = Con();
        if(FetchFromDB($email)){
            echo <<<p
                <article>
                    <h1 class="error-msg">Email already used</h1>
                </article>
            p;
            exit();
        }
        $stmt = $con->prepare("INSERT INTO users_info (nome, cognome, email, password) VALUES (?, ?, ?, ?)");
        // 'ssss' indica che gli sto passando 4 campi di tipo string
        $stmt->bind_param("ssss", $nome, $cognome, $email, $password);
        if(!($stmt->execute())){
            return false;
        }
    }

    function IsInByToken($token)
    {
        $con = Con();
        $stmt = $con->prepare("SELECT nome, cognome, email, Id, IsAdmin, IsBanned FROM users_info WHERE Token = ? AND  Scadenza > now()");
        $stmt->bind_param("s", $token);
        if(!($stmt->execute())){
            return false;
        }
        $risultato = $stmt->get_result();
        return $risultato->fetch_assoc();
    }

    function DeleteCookie($token)
    {
        $con = Con();
        $stmt = $con->prepare("UPDATE users_info SET Token=NULL, Scadenza=NULL WHERE Token=?");
        $stmt->bind_param("s", $token);
        if(!($stmt->execute())){
            return false;
        }
    }

    function Update_Profile($firstname, $lastname, $new_email, $old_email){
        $con = Con();
        $query= $con->prepare("UPDATE users_info SET nome = ?, cognome = ?, email = ? WHERE email = ?");
        $query->bind_param('ssss', $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_SESSION['email']);
        if(!($query->execute())){
            return false;
        }
        $_SESSION['firstname'] = $_POST['firstname'];
        $_SESSION['lastname'] = $_POST['lastname'];
        $_SESSION['email'] = $_POST['email'];
    }


    function BanButton($user){
        echo '<form method="POST" name="ban" action="ban_user.php">';
        echo '<input type="hidden" id="email" name="email" value="'.$user['email'].'">';
        echo '<input type="hidden" id="ban" name="ban" value="'.$user['IsBanned'].'">';
        if ($user['email'] != $_SESSION['email']){
            if (!($user['IsBanned'])){
                echo '<button class="ban" type="submit">Ban user</button>';
            }
            else{
                echo '<button class="ban" type="submit">Unban user</button>';
            }
        }
        else{
            echo '<p class="delete-msg">You cannot ban yourself</p>';
        }
        echo '</form>';
    }

    function DeleteButton($user){
        echo '<form method="POST" name="delete" action="delete_user.php">';
        echo '<input type="hidden" id="Id" name="Id" value="'.$user['Id'].'">';
        if ($user['Id'] == $_SESSION['Id']){
            echo '<p class="delete-msg">You cannot delete yourself</p>';
        }
        else{
            echo '<button class="delete" type="submit">Delete user</button>';
        }
        echo '</form>';
    }

    function AppointAdmin($user){
        echo '<form method="POST" name="admin" action="appoint_admin.php">';
        echo '<input type="hidden" id="email" name="email" value="'.$user['email'].'">';
        echo '<input type="hidden" id="admin" name="admin" value="'.$user['IsAdmin'].'">';
        if ($user['email'] != $_SESSION['email']){
            if (!($user['IsAdmin'])){
                echo '<button class="appoint" type="submit">Appoint admin</button>';
            }
            else{
                echo '<button class="appoint" type="submit">Remove admin</button>';
            }
        }
        else{
            echo '<p class="delete-msg">You are the admin</p>';
        }
        echo '</form>';
    }

function AddToMenu(){
    $con = Con();
    if(IsInMenu($_POST['newdish'])){
        echo <<<p
                <article>
                    <h1 class="error-msg">Dish already on the menu</h1>
                </article>
            p;
        exit();
    }
    $stmt = $con->prepare("INSERT INTO menu (nome_piatto, costo, img, tipo_piatto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('siss', $_POST['newdish'], $_POST['price'], $_POST['imglink'], $_POST['type']);
    if(!($stmt->execute())){
        return false;
    }
    // Salvo in variabili il booleano degli allergeni perchè non posso passare una funzione come argomento di un prep stmt
    $lactose = isset($_POST['lactose']);
    $gluten = isset($_POST['gluten']);
    $egg = isset($_POST['egg']);
    $query = $con->prepare("INSERT INTO allergens (nome_piatto, lattosio, glutine, uova) VALUES (?, ?, ?, ?)");
    // La funzione isset serve a passargli 0 anzichè 'NULL'
    $query->bind_param('siii', $_POST['newdish'],$lactose, $gluten, $egg);
    if(!($query->execute())){
        return false;
    }
}

function IsInMenu($dish_name){
    $con = Con();
    $stmt = $con->prepare("SELECT nome_piatto, tipo_piatto FROM menu WHERE nome_piatto LIKE ?");
    $stmt->bind_param('s',$_POST['newdish']);
    if(!($stmt->execute())){
        return false;
    }
    $dish_list = $stmt->get_result();
    return $dish_list->num_rows;
}

// DISCLAIMER
// Questa funzione contiene una query che restituisce tutti i piatti del menù, ma solo il nome.
// ListaPiatti() restituisce anch'essa i nomi dei piatti, ma anche 6 attributi in più ed inoltre effettua un join,
// risultando quindi più pesante e lenta.
function OnlyDish(){
        try{
            $con = Con();
            $result = $con->query("SELECT nome_piatto FROM menu");
            $dish_list = array();
            while($dish = $result->fetch_array()){
                $dish_list[] = $dish;
            }
            return $dish_list;
        }
        catch(\Throwable){
            return false;
        }

}

function UsersList(){
        try{
            $con = Con();
            $result = $con->query("SELECT nome, cognome, email, IsBanned, IsAdmin, Id FROM users_info");
            $UsersDB = array();
            // Finchè $result non finisce io inizializzo l'i-esimo elemento di $UsersDB con una terna [nome, cognome, email]
            while($user = $result->fetch_array()){
                $UsersDB[] = $user;
            }
            return $UsersDB;
        }
        catch(\Throwable){
            return NULL;
        }
}

function OrderList($id){
        try{
            $con = Con();
            $result = $con->query("SELECT * FROM storico_acquisti WHERE id_utente = '$id';");
            $orderlist = Array();
            while($order = $result->fetch_array()){
                $orderlist[] = $order;
            }
            return $orderlist;
        }
        catch(\Throwable){
            return false;
        }
}

function RemoveDish($dish){
        try{
            if($dish == "none"){
                exit();
                header('location:menu_add_dish.php');
            }
            $con = Con();
            $result1 = $con->query("DELETE FROM menu WHERE nome_piatto = '$dish'");
            $result2 = $con->query("DELETE FROM allergens WHERE nome_piatto = '$dish'");
        }
        catch(\Throwable){
            return false;
        }
}

// Salvo o aggiorno sul bd la valutazione dell'utente
function SalvaValutazione($id_utente, $nome_prodotto, $voto)
{
    $con = Con();
    if (EsisteValutazione($id_utente, $nome_prodotto)) {
        $stmt = $con->prepare("UPDATE valutazioni SET voto = ? WHERE id_utente = ? AND nome_prodotto = ?");
        $stmt->bind_param('iis', $voto, $id_utente, $nome_prodotto);
    } else {
        $stmt = $con->prepare("INSERT INTO valutazioni (id_utente, nome_prodotto, voto) VALUES (?, ?, ?)");
        $stmt->bind_param('isi', $id_utente, $nome_prodotto, $voto);
    }
    if(!($stmt->execute())){
        return false;
    }
}

//controllo se esiste già una valutazione sul quel prodotto da parte dell'utente
function EsisteValutazione($id_utente, $nome_prodotto)
{
    $con = Con();
    $stmt = $con->prepare("SELECT COUNT(*) AS num_valutazioni FROM valutazioni  WHERE id_utente = ? AND  nome_prodotto=?");
    $stmt->bind_param("is", $id_utente, $nome_prodotto);
    if(!($stmt->execute())){
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $num_valutazioni = $row['num_valutazioni'];
    return $num_valutazioni > 0;
}

// Calcola la media voto di un piatto
function CalcolaMediaValutazioni($nome_prodotto)
{
    $con = Con();
    $stmt = $con->prepare("SELECT AVG(voto) AS media_valutazioni FROM valutazioni WHERE nome_prodotto = ?");
    $stmt->bind_param('s', $nome_prodotto);
    if(!($stmt->execute())){
        return false;
    }
    $result = $stmt->get_result();
    if ($result  = $result->fetch_assoc()) {
        return $result['media_valutazioni'];
    } else {
        $a = 1;
        return $a;
    }
}

// Funzione che genera le stelle
function generaStelle($media_voti)
{
    // stringa vuota alla variabile
    $stelle = '<span class="fa fa-star "></span>';

    // Converte la media dei voti in un numero intero per rappresentare le stelle
    $num_stelle_piene = intval($media_voti);
    for ($i = 1; $i <= 5; $i++) {
        if ($i < $num_stelle_piene) {
            $stelle .= '<span class="fa fa-star "></span>'; // concateno altre stelle
        }
    }
    return $stelle;
}

function HaAcquistato($id_utente, $nome_piatto){
    $con = Con();
    $stmt = $con->prepare("SELECT COUNT(*) AS count FROM storico_acquisti WHERE id_utente = ? AND nome_piatto = ?");
    $stmt->bind_param("is", $id_utente, $nome_piatto);
    if(!($stmt->execute())){
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if($row['count'] > 0){
        return true;
    }
}