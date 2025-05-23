document.addEventListener('DOMContentLoaded', () => {
    
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
});