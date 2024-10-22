(function () {

    let tabla = document.getElementById('tablaPokemon');

    if(tabla) {
        tabla.addEventListener('click', clickTable);
    }

    function clickTable(event) {
        let target = event.target;
        if(target.tagName === 'A' && target.getAttribute('class') === 'borrar') {
            confirmDelete(event);
        }
    }

    function confirmDelete(event) {
        if(!confirm('Are you sure you want to delete this Pokémon?')) {
            event.preventDefault();
        }
    }

})();