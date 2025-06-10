// Cart quantity update handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity updates in cart
    const quantityInputs = document.querySelectorAll('.cart-quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Handle form submissions to prevent double submission
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButtons = this.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(button => {
                button.disabled = true;
                button.textContent = 'Processing...';
            });
        });
    });

    // Show/hide password toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type');
            input.setAttribute('type', type === 'password' ? 'text' : 'password');
            this.textContent = type === 'password' ? 'Hide' : 'Show';
        });
    });

    // Flash message auto-hide
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 5000);
        }, 5000);
    });

    // Book card hover effects
    const bookCards = document.querySelectorAll('.book-card');
    bookCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px)';
            card.style.boxShadow = '0 8px 16px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
        });
    });

    // Cart quantity updates
    const quantityInputs = document.querySelectorAll('.cart-quantity');
    quantityInputs.forEach(input => {
        input.addEventListener('change', async (e) => {
            const bookId = e.target.dataset.bookId;
            const quantity = e.target.value;
            
            try {
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ bookId, quantity })
                });
                
                if (!response.ok) throw new Error('Failed to update cart');
                
                const data = await response.json();
                // Update total price
                const totalElement = document.querySelector('.cart-total-price');
                if (totalElement) {
                    totalElement.textContent = `$${data.total.toFixed(2)}`;
                }
            } catch (error) {
                console.error('Error updating cart:', error);
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flash-message flash-error';
                errorDiv.textContent = 'Failed to update cart. Please try again.';
                document.querySelector('main').prepend(errorDiv);
            }
        });
    });

    // Mobile navigation toggle
    const navToggle = document.createElement('button');
    navToggle.className = 'nav-toggle';
    navToggle.innerHTML = '☰';
    navToggle.style.cssText = `
        display: none;
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1000;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem;
        font-size: 1.5rem;
        cursor: pointer;
        border-radius: 4px;
    `;

    const nav = document.querySelector('nav');
    if (nav) {
        nav.parentNode.insertBefore(navToggle, nav);

        navToggle.addEventListener('click', () => {
            nav.classList.toggle('nav-open');
        });

        // Add media query for mobile navigation
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        function handleMobileNav(e) {
            if (e.matches) {
                navToggle.style.display = 'block';
                nav.style.display = 'none';
                nav.classList.remove('nav-open');
            } else {
                navToggle.style.display = 'none';
                nav.style.display = 'block';
            }
        }
        mediaQuery.addListener(handleMobileNav);
        handleMobileNav(mediaQuery);
    }

    // Add to cart animation
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Prevent double-clicks
            if (this.disabled) return;
            this.disabled = true;

            // Create floating cart item
            const rect = this.getBoundingClientRect();
            const floater = document.createElement('div');
            floater.className = 'cart-floater';
            floater.style.cssText = `
                position: fixed;
                left: ${rect.left}px;
                top: ${rect.top}px;
                width: 20px;
                height: 20px;
                background: var(--primary-color);
                border-radius: 50%;
                pointer-events: none;
                z-index: 1000;
                transition: all 0.5s ease-in-out;
            `;
            document.body.appendChild(floater);

            // Get cart icon position
            const cart = document.querySelector('nav a[href="/cart"]');
            if (cart) {
                const cartRect = cart.getBoundingClientRect();
                // Animate to cart
                setTimeout(() => {
                    floater.style.left = `${cartRect.left + cartRect.width/2}px`;
                    floater.style.top = `${cartRect.top + cartRect.height/2}px`;
                    floater.style.transform = 'scale(0)';
                    // Remove floater and re-enable button
                    setTimeout(() => {
                        floater.remove();
                        this.disabled = false;
                    }, 500);
                }, 10);
            }
        });
    });
}); 