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
