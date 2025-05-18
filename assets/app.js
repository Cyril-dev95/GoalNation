// Exécuter le code lorsque le DOM est complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du DOM nécessaires pour la recherche et le filtrage
    const searchInput = document.getElementById('search-input'); // Champ de saisie de recherche
    const suggestionsBox = document.getElementById('suggestions'); // Boîte de suggestions
    const filterForm = document.getElementById('filter-form'); // Formulaire de filtrage
    const productsList = document.getElementById('products-list'); // Liste des produits
    const priceRange = document.getElementById('price-range'); // Curseur de plage de prix
    const priceValue = document.getElementById('price-value'); // Affichage de la valeur du prix

    // Navigation réactive
    const hommeLink = document.getElementById('homme-link'); // Lien pour la section homme
    const hommeSubNav = document.getElementById('homme-sub-nav'); // Sous-navigation pour la section homme
    const femmeLink = document.getElementById('femme-link'); // Lien pour la section femme
    const femmeSubNav = document.getElementById('femme-sub-nav'); // Sous-navigation pour la section femme
    const enfantLink = document.getElementById('enfant-link'); // Lien pour la section enfant
    const enfantSubNav = document.getElementById('enfant-sub-nav'); // Sous-navigation pour la section enfant
    const equipesLink = document.getElementById('equipes-link'); // Lien pour la section équipes
    const equipesSubNav = document.getElementById('equipes-sub-nav'); // Sous-navigation pour la section équipes

    // Basculer l'affichage de la sous-navigation homme lors du clic sur le lien homme
    if (hommeLink && hommeSubNav) {
        hommeLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            hommeSubNav.style.display = hommeSubNav.style.display === 'none' ? 'block' : 'none'; // Basculer l'affichage
        });
    }

    // Basculer l'affichage de la sous-navigation femme lors du clic sur le lien femme
    if (femmeLink && femmeSubNav) {
        femmeLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            femmeSubNav.style.display = femmeSubNav.style.display === 'none' ? 'block' : 'none'; // Basculer l'affichage
        });
    }

    if (enfantLink && enfantSubNav) {
        enfantLink.addEventListener('click', function(event) {
            event.preventDefault();
            enfantSubNav.style.display = enfantSubNav.style.display === 'none' ? 'block' : 'none';
        });
    }

    if (equipesLink && equipesSubNav) {
        equipesLink.addEventListener('click', function(event) {
            event.preventDefault();
            equipesSubNav.style.display = equipesSubNav.style.display === 'none' ? 'block' : 'none';
        });
    }

    // Suggestions de recherche
    if (searchInput && suggestionsBox) {
        // Ajouter un écouteur d'événement à l'élément de saisie de recherche pour l'événement 'input'
        searchInput.addEventListener('input', function() {
            // Obtenir la valeur actuelle de la saisie de recherche
            const query = searchInput.value;

            // Vérifier si la longueur de la requête est supérieure à 2 caractères
            if (query.length > 2) {
                // Récupérer les suggestions du serveur en fonction de la requête
                fetch(`/products/suggestions?q=${encodeURIComponent(query)}`)
                    .then(response => response.json()) // Analyser la réponse JSON
                    .then(data => {
                        // Effacer les suggestions actuelles
                        suggestionsBox.innerHTML = '';
                        // Itérer sur les données (suggestions) reçues du serveur
                        data.forEach(item => {
                            // Créer un nouvel élément div pour chaque suggestion
                            const suggestionItem = document.createElement('div');
                            // Ajouter la classe 'suggestion-item' à l'élément div
                            suggestionItem.classList.add('suggestion-item');
                            // Définir le HTML interne de l'élément div pour inclure un lien avec les détails de la suggestion
                            suggestionItem.innerHTML = `
                                <a href="/products/${item.id}">
                                    <img src="${item.imageUrl}" alt="${item.name}" style="max-width: 50px;">
                                    ${item.name}
                                </a>
                            `;
                            // Ajouter l'élément de suggestion à la boîte de suggestions
                            suggestionsBox.appendChild(suggestionItem);
                        });
                    });
            } else {
                // Si la longueur de la requête est inférieure ou égale à 2, effacer la boîte de suggestions
                suggestionsBox.innerHTML = '';
            }
        });

        // Ajouter un écouteur d'événement au document pour l'événement 'click'
        document.addEventListener('click', function(event) {
            // Vérifier si la cible de l'événement de clic n'est pas l'élément de saisie de recherche ou la boîte de suggestions
            if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                // Effacer la boîte de suggestions
                suggestionsBox.innerHTML = '';
            }
        });
    }

    // Filtrage en temps réel
    if (filterForm && productsList) {
        filterForm.addEventListener('change', function() {
            const formData = new FormData(filterForm);
            const queryString = new URLSearchParams(formData).toString();
            const currentUrl = window.location.pathname;

            fetch(`${currentUrl}?${queryString}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newProductsList = doc.getElementById('products-list');
                    if (newProductsList) {
                        productsList.innerHTML = newProductsList.innerHTML;
                    } else {
                        console.error('Element #products-list non trouvé dans la réponse.');
                    }
                });
        });

        // Mettre à jour l'affichage de la valeur du prix et déclencher le filtrage en temps réel lors du changement de la plage de prix
        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                // Mettre à jour l'affichage de la valeur du prix
                priceValue.textContent = `${priceRange.value}€`;
                // Déclencher l'événement 'change' sur le formulaire de filtrage
                filterForm.dispatchEvent(new Event('change'));
            });
        }
    }
});