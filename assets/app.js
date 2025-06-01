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

    // Suggestions de recherche
    if (searchInput && suggestionsBox) {
        // Ajouter un écouteur d'événement à l'élément de saisie de recherche pour l'événement 'input'
        searchInput.addEventListener('input', function() {
            // Obtenir la valeur actuelle de la saisie de recherche
            const query = searchInput.value;

            // Vérifier si la longueur de la requête est supérieure ou égale à 2 caractères
            if (query.length >= 2) {
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
                                    <img src="/images/${item.imageUrl}.webp" alt="${item.name}" style="max-width: 6vw;">
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

    const header = document.querySelector('.header');

    function hideAllSubNavs() {
        document.querySelectorAll('.sub-nav').forEach(nav => nav.style.display = 'none');
    }

    if (header) {
        header.addEventListener('mouseleave', hideAllSubNavs);
    }    

    function toggleSubNav(linkId, subNavId) {
        const link = document.getElementById(linkId);
        const subNav = document.getElementById(subNavId);

        if (!link || !subNav) return;

        link.addEventListener("mouseenter", function(e) {
            // Ferme toutes les autres sous-nav
            document.querySelectorAll('.sub-nav').forEach(nav => {
                if (nav !== subNav) nav.style.display = 'none';
            });
            // Affiche celle-ci
            subNav.style.display = 'block';
        });
    }

    toggleSubNav('homme-link', 'homme-sub-nav');
    toggleSubNav('femme-link', 'femme-sub-nav');
    toggleSubNav('enfant-link', 'enfant-sub-nav');
    toggleSubNav('equipes-link', 'equipes-sub-nav');

    // Clique en dehors pour fermer
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item') && !e.target.closest('.sub-nav')) {
            document.querySelectorAll('.sub-nav').forEach(nav => nav.style.display = 'none');
        }
    });

    function initCardCarousel(carouselSelector, trackSelector, leftSelector, rightSelector, visibleCards = 3) {
        const carousel = document.querySelector(carouselSelector);
        const track = document.querySelector(trackSelector);
        const leftArrow = document.querySelector(leftSelector);
        const rightArrow = document.querySelector(rightSelector);

        if (!carousel || !track || !leftArrow || !rightArrow) return;

        let cards = Array.from(track.children);
        const totalCards = cards.length;

        // Clone les dernières et premières cards pour le loop
        for (let i = 0; i < visibleCards; i++) {
            track.appendChild(cards[i].cloneNode(true)); // clones début à la fin
            track.insertBefore(cards[totalCards - 1 - i].cloneNode(true), track.firstChild); // clones fin au début
        }

        // Mise à jour de la liste des cards après clonage
        cards = Array.from(track.children);

        let currentIndex = visibleCards; // On commence sur la vraie première card

        function getCardWidth() {
            const cardWidth = cards[0].offsetWidth;
            const gap = parseInt(getComputedStyle(track).gap) || 0;
            return cardWidth + gap;
        }

        function updateCarousel(transition = true) {
            track.style.transition = transition ? "transform 0.4s" : "none";
            track.style.transform = `translateX(-${currentIndex * getCardWidth()}px)`;
        }

        rightArrow.addEventListener('click', () => {
            if (currentIndex < cards.length - visibleCards) {
                currentIndex++;
                updateCarousel();
                // Si on arrive sur un clone, reset après la transition
                if (currentIndex === cards.length - visibleCards) {
                    setTimeout(() => {
                        currentIndex = visibleCards;
                        updateCarousel(false);
                    }, 400);
                }
            }
        });

        leftArrow.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
                // Si on arrive sur un clone, reset après la transition
                if (currentIndex === 0) {
                    setTimeout(() => {
                        currentIndex = cards.length - visibleCards * 2;
                        updateCarousel(false);
                    }, 400);
                }
            }
        });

        window.addEventListener('resize', () => updateCarousel(false));

        // Init
        updateCarousel(false);
    }

    // Nouveautés
    initCardCarousel('.nouveautes-carousel', '.nouveautes-track', '.nouveautes-left', '.nouveautes-right', 3);
    // Top Ventes
    initCardCarousel('.ventes-carousel', '.ventes-track', '.ventes-left', '.ventes-right', 3);
    // Actualités
    initCardCarousel('.news-carousel', '.news-track', '.news-left', '.news-right', 3);
});