document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.add-to-cart-form');

    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // prevent the page reload

            const formData = new FormData(form);

            try {
                const response = await fetch('route.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                console.log('Server response:', result);
                alert('Product added to cart!'); // Replace with toast or UI update if needed

            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add to cart.');
            }
        });
    });
});