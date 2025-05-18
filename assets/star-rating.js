document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating-input');
    let hasVoted = false;

    // Récupère la note moyenne affichée au chargement
    let averageStars = 0;
    stars.forEach((star, idx) => {
        if (star.style.color === 'gold') averageStars = idx + 1;
    });

    if (!stars.length || !ratingInput) return;

    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const val = parseInt(this.dataset.value);
            stars.forEach((s, idx) => {
                s.style.color = idx < val ? 'gold' : '#ccc';
            });
        });
        star.addEventListener('mouseleave', function() {
            if (ratingInput.value > 0) {
                const selected = parseInt(ratingInput.value) / 2;
                stars.forEach((s, idx) => {
                    s.style.color = idx < selected ? 'gold' : '#ccc';
                });
            } else {
                // Remet la moyenne si pas encore voté
                stars.forEach((s, idx) => {
                    s.style.color = idx < averageStars ? 'gold' : '#ccc';
                });
            }
        });
        star.addEventListener('click', function() {
            const val = parseInt(this.dataset.value);
            ratingInput.value = val * 2; // 1 étoile = 2/10
            stars.forEach((s, idx) => {
                s.style.color = idx < val ? 'gold' : '#ccc';
            });
        });
    });

    document.getElementById('star-rating-form').addEventListener('mouseleave', function() {
        if (ratingInput.value > 0) {
            const selected = parseInt(ratingInput.value) / 2;
            stars.forEach((s, idx) => {
                s.style.color = idx < selected ? 'gold' : '#ccc';
            });
        } else {
            stars.forEach((s, idx) => {
                s.style.color = idx < averageStars ? 'gold' : '#ccc';
            });
        }
    });
});