document.addEventListener('DOMContentLoaded', function() {
    function attachListeners() {
        const mainImg = document.getElementById('main-product-image');
        const additionalImages = document.querySelector('.additional-images');
        if (!mainImg || !additionalImages) return;

        additionalImages.querySelectorAll('.thumbnail-image').forEach(function(thumb) {
            thumb.style.cursor = 'pointer';
            thumb.onclick = function() {
                // Clone des images pour éviter les bugs de référence
                const mainClone = mainImg.cloneNode(true);
                const thumbClone = thumb.cloneNode(true);

                // Remplacement dans le DOM
                additionalImages.replaceChild(mainClone, thumb);
                mainImg.parentNode.replaceChild(thumbClone, mainImg);

                // Remettre les bons IDs/classes
                thumbClone.id = 'main-product-image';
                thumbClone.classList.remove('thumbnail-image');
                thumbClone.classList.add('main-image');

                mainClone.removeAttribute('id');
                mainClone.classList.remove('main-image');
                mainClone.classList.add('thumbnail-image');

                // Réattacher les listeners sur les nouvelles miniatures
                attachListeners();
                setupZoom(); // <-- Ajoute ceci pour réactiver le zoom à chaque échange
            };
        });
    }

    // Zoom sur la main image
    function setupZoom() {
        let mainImg = document.getElementById('main-product-image');
        if (!mainImg) return;

        // Supprime une ancienne lentille si elle existe
        let oldLens = mainImg.parentElement.querySelector('.zoom-lens');
        if (oldLens) oldLens.remove();

        const zoomLens = document.createElement("div");
        zoomLens.classList.add("zoom-lens");
        mainImg.parentElement.appendChild(zoomLens);

        mainImg.addEventListener("mousemove", function (e) {
            const rect = mainImg.getBoundingClientRect();
            const lensSize = zoomLens.offsetWidth / 2;

            let mouseX = e.clientX - rect.left;
            let mouseY = e.clientY - rect.top;

            // Empêcher la lentille de dépasser l'image
            if (mouseX < lensSize) mouseX = lensSize;
            if (mouseY < lensSize) mouseY = lensSize;
            if (mouseX > rect.width - lensSize) mouseX = rect.width - lensSize;
            if (mouseY > rect.height - lensSize) mouseY = rect.height - lensSize;

            // Positionner la lentille sur l'image
            zoomLens.style.left = (mainImg.offsetLeft + mouseX - lensSize) + "px";
            zoomLens.style.top = (mainImg.offsetTop + mouseY - lensSize) + "px";
            zoomLens.style.display = "block";

            // Appliquer l'effet de zoom
            zoomLens.style.backgroundImage = `url(${mainImg.src})`;
            zoomLens.style.backgroundSize = `${rect.width * 2}px ${rect.height * 2}px`; // Zoom x2
            zoomLens.style.backgroundPosition = `-${(mouseX * 2) - lensSize}px -${(mouseY * 2) - lensSize}px`;
        });

        mainImg.addEventListener("mouseleave", function () {
            zoomLens.style.display = "none";
        });
    }

    attachListeners();
    setupZoom();
});