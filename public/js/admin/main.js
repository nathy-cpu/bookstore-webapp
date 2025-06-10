document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmations
    document.querySelectorAll('[data-confirm]').forEach(function(element) {
        element.addEventListener('submit', function(e) {
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // Handle alerts auto-hide
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
}); 