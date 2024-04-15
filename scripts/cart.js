// Aggiungo un oggetto al local storage
function add_item (nomepiatto) {
    let quantita = document.getElementById(nomepiatto);
    let n = Number(sessionStorage.getItem(nomepiatto));
    if (n){sessionStorage.setItem(nomepiatto,Number(quantita.value)+n);}
    else{ sessionStorage.setItem(nomepiatto, quantita.value);}
}

// Rimuovo un oggetto dal database
function remove_item (nomepiatto) {
    sessionStorage.removeItem(nomepiatto);
    location.href=location.href;
}
//funzione manda
function manda() {
    // prendo tutti le chiavi e tutti i valori nel local storage
    let chiavi = Object.keys(sessionStorage);
    chiavi.sort();
    let valori = [];
    for(i=0; i<chiavi.length; i++) {
        valori.push(sessionStorage.getItem(chiavi[i]));
    }

    // creo un oggetto(JSON) carrello
    let carrello = {
        keys: chiavi,
        values: valori
    };

    // creo il form
    var form = document.createElement('form');
    form.style.display = 'none';
    form.method = 'POST';
    form.action = 'cart.php';

    // assegno al form il nome carrello e lo
    var input = document.createElement('input');
    input.name = "carrello";
    input.value = JSON.stringify(carrello);
    console.log(input.value);
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}

