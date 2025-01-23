function toggleTableBody(id) {
    var tableWrapper = document.getElementById("table-wrapper-" + id);
    var chevron = document.getElementById("chevron-" + id);
    
    if (tableWrapper.style.display === "none" || tableWrapper.classList.contains('collapsed')) {
        tableWrapper.style.display = "block"; // Afficher
        tableWrapper.classList.remove('collapsed');
        
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    } else {
        tableWrapper.style.display = "none"; // Masquer
        tableWrapper.classList.add('collapsed');
        
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    }
}



function toggleGroup(groupeId) {
    var groupTable = document.getElementById("table-wrapper-" + groupeId);
    var chevron = document.getElementById("chevron-" + groupeId);
    
    if (groupTable.classList.contains('collapsed')) {
        groupTable.classList.remove('collapsed');
        
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    } else {
        groupTable.classList.add('collapsed');
        
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    }
}

function updateGroupNotes(groupeId) {
    var globalNote = document.getElementById("global-note-" + groupeId).value;
    var elevesNotes = document.querySelectorAll("#table-wrapper-" + groupeId + " input[name^='note_']");
    if(globalNote<0){
        globalNote=0;
        document.getElementById("global-note-" + groupeId).value=0;
    }else if(globalNote>20){
        globalNote=20;
        document.getElementById("global-note-" + groupeId).value=20;
    }
        
    elevesNotes.forEach(function(input) {
        input.value = globalNote;
    });
}

// fonction pour empêcher le repliage du tableau quand on clique sur l'input
function preventGroupToggle(event, groupeId) {
    event.stopPropagation(); 
}



document.addEventListener('DOMContentLoaded', () => {
    // Gestion des boutons dropdown
    const dropdownButtons = document.querySelectorAll('.dropdown-toggle');
    dropdownButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const dropdownId = this.dataset.id; // Récupère l'ID unique (idEval)
            const dropdownContent = document.querySelector(`#dropdownContent_${dropdownId}`);

            // Ferme tous les autres dropdowns
            document.querySelectorAll('.dropdownContent').forEach((content) => {
                if (content !== dropdownContent) {
                    content.style.display = 'none';
                }
            });

            const isVisible = dropdownContent.style.display === 'block';
            dropdownContent.style.display = isVisible ? 'none' : 'block';

            if (!isVisible) {
                const searchInput = dropdownContent.querySelector('.searchInput');
                searchInput.focus();
            }
        });
    });

    // Gestion de la recherche
    const searchInputs = document.querySelectorAll('.searchInput');
    searchInputs.forEach((searchInput) => {
        searchInput.addEventListener('input', function () {
            const dropdownId = this.dataset.id; // ID unique du dropdown (idEval)
            const filter = this.value.toLowerCase();
            const checkboxes = document.querySelectorAll(`#dropdownContent_${dropdownId} .form-check`);

            checkboxes.forEach((checkbox) => {
                const label = checkbox.querySelector('label').textContent.toLowerCase();
                checkbox.style.display = label.includes(filter) ? 'block' : 'none';
            });
        });
    });

    // Gestion des clics en dehors du dropdown
    document.addEventListener('click', function (event) {
        const isClickInside = event.target.closest('.dropdown');
        if (!isClickInside) {
            document.querySelectorAll('.dropdownContent').forEach((content) => {
                content.style.display = 'none';
            });
        }
    });

    // Mise à jour du texte du bouton dropdown
    const checkboxes = document.querySelectorAll('.form-check-input');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function () {
            const dropdownId = this.closest('.dropdownContent').id.split('_')[1];
            const button = document.querySelector(`#dropdownButton_${dropdownId}`);
            const selected = Array.from(
                document.querySelectorAll(`#dropdownContent_${dropdownId} .form-check-input:checked`)
            ).map((input) => {
                const label = input.closest('.form-check').querySelector('label').textContent.trim();
                return label;
            });

            button.textContent = selected.length > 0 ? selected.join(', ') : 'Rechercher des intervenants';
        });
    });
});
