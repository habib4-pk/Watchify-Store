document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (e) {
        const price = document.querySelector('input[name="price"]').value;
        const stock = document.querySelector('input[name="stock"]').value;

        if (price <= 0) {
            alert('Price must be greater than 0');
            e.preventDefault();
        } else if (stock <= 0) {
            alert('Stock must be greater than 0');
            e.preventDefault();
        }
    });
});
