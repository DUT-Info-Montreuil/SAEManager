// Fonction pour afficher/masquer le tableau complet (en-tête + corps) et changer le chevron
function toggleTableBody(id) {
    var tableWrapper = document.getElementById("table-wrapper-" + id);
    var chevron = document.getElementById("chevron-" + id);
    
    // Vérifie si le tableau est déjà replié
    if (tableWrapper.style.display === "none" || tableWrapper.classList.contains('collapsed')) {
        // Déplier le tableau
        tableWrapper.style.display = "block"; // Afficher
        tableWrapper.classList.remove('collapsed');
        
        // Changer l'icône du chevron en haut
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    } else {
        // Replier le tableau
        tableWrapper.style.display = "none"; // Masquer
        tableWrapper.classList.add('collapsed');
        
        // Changer l'icône du chevron en bas
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    }
}
