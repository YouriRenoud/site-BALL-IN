document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById('search');
    const suggestions = document.getElementById('suggestions');
    const productGrid = document.getElementById('product-grid');

    if (searchInput && suggestions && productGrid) {
        searchInput.addEventListener('keyup', () => {
            const term = searchInput.value.trim();
            if (term.length < 2) {
                suggestions.style.display = 'none';
                return;
            }

            fetch(`search.php?q=${encodeURIComponent(term)}`)
                .then(res => res.text())
                .then(html => {
                    suggestions.innerHTML = html;
                    suggestions.style.display = 'block';
                });
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('suggestion-item')) {
                const term = e.target.textContent;

                fetch(`search.php?q=${encodeURIComponent(term)}&mode=full`)
                    .then(res => res.text())
                    .then(html => {
                        productGrid.innerHTML = html;
                    });

                suggestions.style.display = 'none';
                searchInput.value = term;
            }
        });
    }
});
