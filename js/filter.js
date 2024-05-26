document.addEventListener('DOMContentLoaded', function () {
    const sizeFilter = document.getElementById('size-filter');
    const colorFilter = document.getElementById('color-filter');

    sizeFilter.addEventListener('change', filterProducts);
    colorFilter.addEventListener('change', filterProducts);

    function filterProducts() {
        const size = sizeFilter.value;
        const color = colorFilter.value;

        const data = new FormData();
        data.append('action', 'pfp_filter_products');
        data.append('size', size);
        data.append('color', color);

        fetch(pfp_ajax.ajax_url, {
            method: 'POST',
            body: data
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('product-list').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }
});
