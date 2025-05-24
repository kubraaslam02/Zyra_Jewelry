document.addEventListener('DOMContentLoaded', () => {
    // Toggle Mobile Menu
    const menuBtn = document.getElementById('menu-button');
    const menu = document.getElementById('mobile-menu');

    if (menuBtn && menu) {
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }

    // Add to Cart Form Submission
    const forms = document.querySelectorAll('.add-to-cart-form');

    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch('route.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                console.log('Server response:', result);
                alert('Product added to cart!');
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add to cart.');
            }
        });
    });

    // Payment Method Toggle
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardInfoSection = document.getElementById('card-info');

    function toggleCardInfo() {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (selected && selected.value === 'card') {
            cardInfoSection.style.display = 'block';
        } else {
            cardInfoSection.style.display = 'none';
        }
    }

    if (paymentRadios.length > 0 && cardInfoSection) {
        toggleCardInfo(); // Run on page load
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', toggleCardInfo);
        });
    }
});