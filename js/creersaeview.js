// Barre de recherche pour les élèves
document.getElementById('searchEleves').addEventListener('input', function () {
    const searchQuery = this.value.toLowerCase();
    const eleveItems = document.querySelectorAll('.eleve-item');

    eleveItems.forEach(item => {
        const label = item.querySelector('label').textContent.toLowerCase();
        if (label.includes(searchQuery)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Barre de recherche pour les co-responsables
document.getElementById('searchCoResponsables').addEventListener('input', function () {
    const searchQuery = this.value.toLowerCase();
    const coResponsableItems = document.querySelectorAll('.co-responsable-item');

    coResponsableItems.forEach(item => {
        const label = item.querySelector('label').textContent.toLowerCase();
        if (label.includes(searchQuery)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Barre de recherche pour les intervenants
document.getElementById('searchIntervenants').addEventListener('input', function () {
    const searchQuery = this.value.toLowerCase();
    const intervenantItems = document.querySelectorAll('.intervenant-item');

    intervenantItems.forEach(item => {
        const label = item.querySelector('label').textContent.toLowerCase();
        if (label.includes(searchQuery)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
