// Attendez que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    console.log("Script chargé");

    // Ajoutez un gestionnaire d'événements au champ de recherche
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();

            // Boucle à travers chaque ligne de la table et masquer/afficher en fonction de la valeur de recherche
            var rows = document.querySelectorAll('#table tbody tr');

            rows.forEach(function(row) {
                var name = row.textContent.toLowerCase();

                if (name.includes(searchValue)) {
                    row.style.display = ''; // Afficher la ligne si le nom correspond à la valeur de recherche
                } else {
                    row.style.display = 'none'; // Masquer la ligne si le nom ne correspond pas à la valeur de recherche
                }
            });
        });
    }

//////////////////////
var sortOrder = document.getElementById('sortOrder');
    if (sortOrder) {
        sortOrder.addEventListener('change', function() {
            var order = sortOrder.value;
            var rows = Array.from(document.querySelectorAll('#table tbody tr'));

            rows.sort(function(a, b) {
                var quantityA = parseInt(a.querySelector('.quantite').textContent);
                var quantityB = parseInt(b.querySelector('.quantite').textContent);

                if (order === 'asc') {
                    return quantityA - quantityB;
                } else {
                    return quantityB - quantityA;
                }
            });

            var tbody = document.querySelector('#table tbody');
            rows.forEach(function(row) {
                tbody.appendChild(row);
            });
        });
    }});