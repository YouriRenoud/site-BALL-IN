document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById('search');
    const suggestions = document.getElementById('suggestions');

    if (!searchInput || !suggestions) return;

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
            const id = e.target.dataset.id;
            window.location.href = `product.php?id=${id}`;
        }
    });
});
