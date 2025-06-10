// Admin-specific JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }
        });
    });

    // Handle delete confirmations
    document.querySelectorAll('.delete-confirm').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
}); 