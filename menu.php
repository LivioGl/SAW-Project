<!DOCTYPE Html>
<html lang="it">
<head>
    <title>Menù</title>
    <link rel="stylesheet" type="text/css" href="./styles/menu.css">
    <link rel="stylesheet" type="text/css" href="./styles/nav.css">
    <script src="https://kit.fontawesome.com/4d9e73afea.js" crossorigin="anonymous"></script>
    <script src="./scripts/cart.js" type="application/javascript"></script>
    <script src="./scripts/menu_bar.js"></script>
</head>
<body>
    <?php
    require_once "nav.php";
    require_once "server_settings.php";
    require_once "functions.php";
    // Query che prende tutti i piatti dal DB
    $tutti_piatti = ListaPiatti();
    // Query che conta quanti piatti ci sono per ogni categoria (il menu viene ordinato in base a questo)
        $piatti_contati = CountPiatti();
        ?>
    <section>
            <!-- Seleziona tipo di piatto -->
            <label for="tipi_piatto">Choose a dish:</label>
            <select name="tipi_piatto" id="tipi_piatto" onchange="SelectDish()">
                <option value="all" id="all" >Tutti i piatti</option>
                <option value="primi" id="option1" >Primi</option>
                <option value="secondi" id="option2">Secondi</option>
                <option value="pizza" id="option3">Pizze</option>
            </select>
            <!-- Cerca un piatto specifico -->
            <div class="search_container">
                <input type="text" placeholder="Search a dish" name="search" id="searchbar" onkeyup="search_dish()">
            </div>
            <!-- Legenda dei simboli per gli allergeni -->
            <div class="legend">
                <ul>
                    <li><i id='gluten' class='fa-solid fa-wheat-awn'></i> = gluten</li>
                    <li><i id='lactose' class='fa-solid fa-cow'></i> = lactose</li>
                    <li><i id='egg' class='fa-solid fa-egg'></i> = egg</li>
                </ul>
            </div>
    </section>
        <?php
        // Organizzo il menù in base ai primi, ai secondi e alle pizze
        while($categoria_piatti = $piatti_contati->fetch_assoc()){
        // Creo un div per ognuno dei tre tipi di piatti
            echo "<div class='div1' id='".$categoria_piatti["tipo_piatto"]."' value = ' ".$categoria_piatti["tipo_piatto"]." '>";
            for($i = 0; $i < $categoria_piatti["amount"]; $i++){
        // Stampo tutti i piatti del menù
                $singolo_piatto = $tutti_piatti->fetch_assoc();
                ?>
                 <article>
                     <!-- Immagine del piatto -->
                     <img src="<?php echo $singolo_piatto["img"]; ?>" alt="<?php echo $singolo_piatto["nome_piatto"];?>">
                     <!-- Nome del piatto -->
                     <h1 class="dish_text"> <?php echo $singolo_piatto["nome_piatto"]; ?> </h1>
                     <!-- Costo del piatto e allergeni contenuti -->
                     <div class="price_allergen">
                         <p class="price">€ <?php echo $singolo_piatto["costo"]; ?>.00</p>
                         <p class="p_allergen"><?php Allergens($singolo_piatto);?></p>
                     </div>
                     <!-- Valutazione media del piatto e stelle (num approx per eccesso) -->
                     <p class="stars">
                         <?php
                         $avg_rating =  CalcolaMediaValutazioni($singolo_piatto["nome_piatto"])?>
                         <b><?php echo number_format(floatval($avg_rating), 1)?>/5</b>
                         <?php
                         $stelle_html = generaStelle($avg_rating);
                         echo $stelle_html;?>
                     </p>
                     <div class="logged_users_functions">
                     <!-- Valutazione (se loggati, non bannati e si ha acquistato il piatto) -->
                         <div class="order_review">
                             <?php
                             if (IsLogged() && $_SESSION['IsBanned'] != 1) {
                                 if(HaAcquistato($_SESSION['Id'],$singolo_piatto["nome_piatto"])){ ?>
                                     <form action="submit_review.php" method="post">
                                         <input type="hidden" name="piatto" id="piatto" value="<?php echo $singolo_piatto["nome_piatto"]; ?>">
                                         <label for="voto"></label>
                                         <select name="voto" id="voto">
                                             <option value="5">5</option>
                                             <option value="4">4</option>
                                             <option value="3">3</option>
                                             <option value="2">2</option>
                                             <option value="1">1</option>
                                         </select>
                                         <button type="submit" name="submit" value="submit">Review</button>
                                     </form>
                             <?php }?>
                         </div>
                     <!-- Ordinazione (se loggati e non bannati) -->
                         <div class="order_review">
                             <label for="<?php echo $singolo_piatto['nome_piatto']?>">Quantità:</label>
                             <select id="<?php echo $singolo_piatto['nome_piatto']?>" name="qty" class="quantitàStyle">
                                 <option value="1">1</option>
                                 <option value="2">2</option>
                                 <option value="3">3</option>
                             </select>
                             <button type="submit" class="btn-primary" name="add_to_cart" onclick="add_item('<?php echo $singolo_piatto['nome_piatto']?>')"> Order </button>
                         </div>
                     <?php }?>
                 </div>
                 </article>
        <?php }
        echo "</div>";
        }?>
</body></html>
