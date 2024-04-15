
// Funzione per filtrare il menù in base alla tipologia di piatto
function SelectDish(){
    let tipi_piatto = document.getElementById("tipi_piatto")
    let selected_value = tipi_piatto.options[tipi_piatto.selectedIndex].value;
    switch(selected_value){
        case 'all':
        {
            document.getElementById("primo").style.display = "flex";
            document.getElementById("secondo").style.display = "flex";
            document.getElementById("pizza").style.display = "flex";
            break;
        }
        case 'primi':
        {
            document.getElementById("primo").style.display = "flex";
            document.getElementById("secondo").style.display = "none";
            document.getElementById("pizza").style.display = "none";
            break;
        }
        case 'secondi':
        {
            document.getElementById("primo").style.display = "none";
            document.getElementById("secondo").style.display = "flex";
            document.getElementById("pizza").style.display = "none";
            break;
        }
        case 'pizza':
        {
            document.getElementById("primo").style.display = "none";
            document.getElementById("secondo").style.display = "none";
            document.getElementById("pizza").style.display = "flex";
            break;
        }
    }
}


// Funzione per cercare nel menù in base al nome del piatto
function search_dish(){
    let input = document.getElementById('searchbar').value;
    input = input.toLowerCase();
    let x = document.getElementsByClassName('div1');

    for(i = 0; i < x.length; i++){
        let b = x[i].children;
        for (j = 0; j < b.length; j++){
            if(!b[j].innerHTML.toLowerCase().includes(input)){
                b[j].style.display = "none";
            }
            else{
                b[j].style.display = "unset";
            }
        }
    }
}



