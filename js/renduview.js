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



// Fonction pour afficher/masquer les élèves d'un groupe et changer le chevron
function toggleGroup(groupeId) {
    var groupTable = document.getElementById("table-wrapper-" + groupeId);
    var chevron = document.getElementById("chevron-" + groupeId);
    
    // Vérifie si le tableau est déjà replié
    if (groupTable.classList.contains('collapsed')) {
        // Déplier le tableau
        groupTable.classList.remove('collapsed');
        
        // Changer l'icône du chevron en haut
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    } else {
        // Replier le tableau
        groupTable.classList.add('collapsed');
        
        // Changer l'icône du chevron en bas
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    }
}

// Fonction pour mettre à jour la note de tous les élèves du groupe
function updateGroupNotes(groupeId) {
    var globalNote = document.getElementById("global-note-" + groupeId).value;
    var elevesNotes = document.querySelectorAll("#table-wrapper-" + groupeId + " input[name^='note_']");
    
    elevesNotes.forEach(function(input) {
        input.value = globalNote;
    });
}

// Fonction pour empêcher le repliage du tableau quand on clique sur l'input
function preventGroupToggle(event, groupeId) {
    event.stopPropagation(); // Empêche la propagation de l'événement "click" vers l'élément parent
}

