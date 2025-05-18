document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var orderId = btn.getAttribute('data-order');
            var row = document.getElementById('details-' + orderId);
            // On considère caché si display est 'none' OU vide
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
                btn.textContent = '-';
            } else {
                row.style.display = 'none';
                btn.textContent = '+';
            }
        });
    });
});