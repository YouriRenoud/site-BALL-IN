window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchTerm = urlParams.get('search');
    
    if (searchTerm) {
        const highlightedRow = document.querySelector('.highlight');
        
        if (highlightedRow) {
            highlightedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}
