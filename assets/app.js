document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du DOM nécessaires pour la recherche et le filtrage
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('suggestions');
    const filterForm = document.getElementById('filter-form');
    const productsList = document.getElementById('products-list');
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');

    // Suggestions de recherche
    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            const query = searchInput.value;
            if (query.length >= 2) {
                fetch(`/produits/suggestions?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        data.forEach(item => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.classList.add('suggestion-item');
                            suggestionItem.innerHTML = `
                                <a href="/produits/${item.id}" class="suggestion-link">
                                    <div class="suggestion-img-wrapper">
                                        <img src="/images/${item.imageUrl}.webp" alt="${item.name}" class="suggestion-img">
                                    </div>
                                    <div class="suggestion-info">
                                        <span class="suggestion-name">${item.name}</span>
                                        ${item.team ? `<span class="suggestion-team">${item.team}</span>` : ''}
                                        ${item.price ? `<span class="suggestion-price">${item.price}€</span>` : ''}
                                    </div>
                                </a>
                            `;
                            suggestionsBox.appendChild(suggestionItem);
                        });
                    });
            } else {
                suggestionsBox.innerHTML = '';
            }
        });

        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
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
            // Utilisation du debounce pour éviter les requêtes multiples
            const debouncedPriceChange = debounce(function() {
                filterForm.dispatchEvent(new Event('change'));
            }, 300);

            priceRange.addEventListener('input', function() {
                priceValue.textContent = `${priceRange.value}€`;
                debouncedPriceChange();
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
            document.querySelectorAll('.sub-nav').forEach(nav => {
                if (nav !== subNav) nav.style.display = 'none';
            });
            subNav.style.display = 'block';
        });
    }

    toggleSubNav('homme-link', 'homme-sub-nav');
    toggleSubNav('femme-link', 'femme-sub-nav');
    toggleSubNav('enfant-link', 'enfant-sub-nav');
    toggleSubNav('equipes-link', 'equipes-sub-nav');

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item') && !e.target.closest('.sub-nav')) {
            document.querySelectorAll('.sub-nav').forEach(nav => nav.style.display = 'none');
        }
    });

    // Initialiser le carrousel de cartes
    function initCardCarousel(carouselSelector, trackSelector, leftSelector, rightSelector, visibleCards = 3) {
        const carousel = document.querySelector(carouselSelector);
        const track = document.querySelector(trackSelector);
        const leftArrow = document.querySelector(leftSelector);
        const rightArrow = document.querySelector(rightSelector);

        if (!carousel || !track || !leftArrow || !rightArrow) return;

        let cards = Array.from(track.children);
        const totalCards = cards.length;

        for (let i = 0; i < visibleCards; i++) {
            track.appendChild(cards[i].cloneNode(true));
            track.insertBefore(cards[totalCards - 1 - i].cloneNode(true), track.firstChild);
        }

        cards = Array.from(track.children);

        let currentIndex = visibleCards;

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
                if (currentIndex === 0) {
                    setTimeout(() => {
                        currentIndex = cards.length - visibleCards * 2;
                        updateCarousel(false);
                    }, 400);
                }
            }
        });

        window.addEventListener('resize', () => updateCarousel(false));
        updateCarousel(false);
    }

    // Nouveautés
    initCardCarousel('.nouveautes-carousel', '.nouveautes-track', '.nouveautes-left', '.nouveautes-right', 3);
    // Top Ventes
    initCardCarousel('.ventes-carousel', '.ventes-track', '.ventes-left', '.ventes-right', 3);
    // Actualités
    initCardCarousel('.news-carousel', '.news-track', '.news-left', '.news-right', 3);

    ['homme-link', 'femme-link', 'enfant-link', 'equipes-link'].forEach(id => {
        const link = document.getElementById(id);
        if (link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
            });
        }
    });

    ['homme-link-footer', 'femme-link-footer', 'enfant-link-footer', 'equipes-link-footer'].forEach(id => {
        const link = document.getElementById(id);
        if (link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
            });
        }
    });

    // Gestion du filtre responsive (bouton à partir de 1130px)
    const openFilterBtn = document.getElementById('open-filter-btn');
    if (openFilterBtn && filterForm) {
        openFilterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterForm.classList.toggle('open');
        });

        // Fermer le filtre si on clique en dehors
        document.addEventListener('click', function(e) {
            if (
                filterForm.classList.contains('open') &&
                !filterForm.contains(e.target) &&
                e.target !== openFilterBtn
            ) {
                filterForm.classList.remove('open');
            }
        });
    }
});