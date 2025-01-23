/**
 * Gestion du boutton "Sélectionner fichier" dans les modal "Rendu" et "Support"
 */
const selectFileButtonList = document.querySelectorAll(
  '[class*="selectFileButton-"]'
);
selectFileButtonList.forEach((element) => {
  element;
});

/**
 * Gestion du modal (pop up) "Dépot Rendu"
 **/

const modalDepotRendu = document.getElementById("modalDepotRendu");
const dropRenduFileCancelButton = document.getElementById(
  "depotCancelButtonRendu"
);
const dropRenduCancelButton = document.getElementById("modalCancelButtonRendu");
const listRenduButtons = document.querySelectorAll('[class*="rendudrop-"]');
const idSaeDepotRendu = document.getElementById("idSaeDepotRendu");

document
  .getElementById("selectFileButtonRendu")
  .addEventListener("click", function () {
    document.getElementById("fileInputRendu").click();
  });

dropRenduFileCancelButton.addEventListener("click", function () {
  modalDepotRendu.classList.remove("d-block");
});

dropRenduCancelButton.addEventListener("click", function () {
  modalDepotRendu.classList.remove("d-block");
});

listRenduButtons.forEach((element) => {
  const matches = element.className.match(/rendudrop-(\d+)/);
  if (matches) {
    const number = matches[1];
    element.addEventListener("click", function () {
      idSaeDepotRendu.value = number;
      modalDepotRendu.classList.add("d-block");
    });
  }
});

/**
 * Gestion du modal (pop up) "Dépot Support"
 **/

// const listSupportButtons = document.querySelectorAll('[class*="supportdrop-"]');
const idSaeDepotSupport = document.getElementById("idSaeDepotSupport");
const modalDepotSupport = document.getElementById("modalDepotSupport");
const dropSupportFileCancelButton = document.getElementById(
  "depotCancelButtonSupport"
);
const dropSupportCancelButton = document.getElementById(
  "modalCancelButtonSupport"
);
const listSupportButtons = document.querySelectorAll(
  '[class*="rendusoutenance-"]'
);

document
  .getElementById("selectFileButtonSupport")
  .addEventListener("click", function () {
    document.getElementById("fileInputSupport").click();
  });

dropSupportFileCancelButton.addEventListener("click", function () {
  modalDepotSupport.classList.remove("d-block");
});

dropSupportCancelButton.addEventListener("click", function () {
  modalDepotSupport.classList.remove("d-block");
});

listSupportButtons.forEach((element) => {
  const matches = element.className.match(/rendusoutenance-(\d+)/);
  if (matches) {
    const number = matches[1];
    element.addEventListener("click", function () {
      console.log("support button clicked");
      idSaeDepotSupport.value = number;
      modalDepotSupport.classList.add("d-block");
    });
  }
});

// Rendu

const createRenduBtn = document.querySelector("#create-rendu");

if (createRenduBtn) {
  createRenduBtn.addEventListener("click", function () {
    document.querySelector("#modalCreateRendu").classList.add("d-block");

    document
      .querySelector("#modal-rendu-cancel")
      .addEventListener("click", function () {
        document.querySelector("#modalCreateRendu").classList.remove("d-block");
      });
  });
}

const editRenduBtn = document.querySelectorAll("#edit-rendu-btn");

editRenduBtn.forEach((btn) => {
  btn.addEventListener("click", function () {
    document.querySelector("#modalModifierRendu").classList.add("d-block");
    const matches = btn.className.match(/modalModifierRendu(\d+)/);
    document.getElementById("idRenduAModifier").value = matches[1];
    document
      .querySelector("#modal-rendu-edit-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalModifierRendu")
          .classList.remove("d-block");
      });
  });
});

const modalCancelButtonCreateRendu = document.getElementById(
  "modalCancelButtonCreateRendu"
);

modalCancelButtonCreateRendu.addEventListener("click", function () {
  document.querySelector("#modalCreateRendu").classList.remove("d-block");
});

const modalCancelButtonModifierRendu = document.getElementById(
  "modalCancelButtonModifierRendu"
);

modalCancelButtonModifierRendu.addEventListener("click", function () {
  document.querySelector("#modalModifierRendu").classList.remove("d-block");
});

// Ajout coeff

document.addEventListener("DOMContentLoaded", () => {
  const checkBox = document.getElementById("renduNote");
  const coeffInput = document.getElementById("coeffInput");
  const form = document.getElementById("formCreateRendu");

  checkBox.addEventListener("change", () => {
    if (checkBox.checked) {
      coeffInput.style.display = "block";
      document.getElementById("coeff").required = true;
    } else {
      coeffInput.style.display = "none";
      document.getElementById("coeff").required = false;
    }
  });

  if (form) {
    form.addEventListener("submit", (event) => {
      const coeff = document.getElementById("coeff").value;
      if (checkBox.checked && (coeff === "" || coeff <= 0)) {
        alert("Veuillez remplir le coefficient pour un rendu noté.");
        event.preventDefault();
      }
      idSaeDepotSupport.value = matches[1];
      modalDepotSupport.classList.add("d-block");
    });
  }
});

/**
 * Gestion du modal (pop-up) "Suprimmer Rendu"
 */

const modalSupressionRendu = document.getElementById(
  "modalSupressionDepotRendu"
);
const modalSupressionRenduCancelButton = document.getElementById(
  "modalSupressionDepotRendu"
);
const modalValiderRendu = document.getElementById("modalValiderDepotRendu");

const listSupressionRenduButtons = document.querySelectorAll(
  '[class*="supressRenduButton"]'
);

listSupressionRenduButtons.forEach((element) => {
  element.addEventListener("click", function () {
    matches = element.className.match(/supressRenduButton-(\d+)/);
    document.getElementById("idDepotSupressionRendu").value = matches[1];
    modalSupressionRendu.classList.add("d-block");
  });
});
modalSupressionRenduCancelButton.addEventListener("click", function () {
  modalSupressionRendu.classList.remove("d-block");
});

/**
 * Gestion du modal (pop-up) "Suprimmer Support"
 */

const modalSupressionSupport = document.getElementById(
  "modalSupressionDepotSupport"
);
const modalSupressionSupportCancelButton = document.getElementById(
  "modalSupressionDepotSupport"
);
const modalValiderSupport = document.getElementById("modalValiderDepotSupport");

const listSupressionSupportButtons = document.querySelectorAll(
  '[class*="supressSupportButton-"]'
);
listSupressionSupportButtons.forEach((element) => {
  element.addEventListener("click", function () {
    matches = element.className.match(/supressSupportButton-(\d+)/);
    document.getElementById("idDepotSupressionSupport").value = matches[1];
    modalSupressionSupport.classList.add("d-block");
  });
});
modalSupressionSupportCancelButton.addEventListener("click", function () {
  modalSupressionSupport.classList.remove("d-block");
});

// soutenance

const createSupportBtn = document.querySelector("#create-soutenance");

if (createSupportBtn) {
  createSupportBtn.addEventListener("click", function () {
    document.querySelector("#modalCreateSoutenance").classList.add("d-block");

    document
      .querySelector("#modal-soutenance-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalCreateSoutenance")
          .classList.remove("d-block");
      });
  });
}

const editSoutenanceBtn = document.querySelectorAll("#edit-soutenance-btn");

editSoutenanceBtn.forEach((btn) => {
  btn.addEventListener("click", function () {
    document.querySelector("#modalModifierSoutenance").classList.add("d-block");
    const matches = btn.className.match(/modalModifierSoutenance(\d+)/);
    document.getElementById("idSoutenanceAModifier").value = matches[1];
    document
      .querySelector("#modal-soutenance-edit-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalModifierSoutenance")
          .classList.remove("d-block");
      });
  });
});

const modalCancelButtonModifierSoutenance = document.getElementById(
  "modalCancelButtonModifierSoutenance"
);
const modalCancelButtonCreateSoutenance = document.getElementById(
  "modalCancelButtonCreateSoutenance"
);

modalCancelButtonModifierSoutenance.addEventListener("click", function () {
  document
    .querySelector("#modalModifierSoutenance")
    .classList.remove("d-block");
});

modalCancelButtonCreateSoutenance.addEventListener("click", function () {
  document.querySelector("#modalCreateSoutenance").classList.remove("d-block");
});

// Champ

const createChampBtn = document.querySelector("#create-champ");

if (createChampBtn) {
  createChampBtn.addEventListener("click", function () {
    document.querySelector("#modalCreateChamp").classList.add("d-block");

    document
      .querySelector("#modal-champ-cancel")
      .addEventListener("click", function () {
        document.querySelector("#modalCreateChamp").classList.remove("d-block");
      });
  });
}

const modalCancelButtonCreateChamp = document.getElementById(
  "modalCancelButtonCreateChamp"
);

modalCancelButtonCreateChamp.addEventListener("click", function () {
  document.querySelector("#modalCreateChamp").classList.remove("d-block");
});

// SUJET

const editSujetBtn = document.querySelector("#edit-sujet");

if (editSujetBtn) {
  editSujetBtn.addEventListener("click", function () {
    document.querySelector("#modalModifierSujet").classList.add("d-block");

    document
      .querySelector("#modal-sujet-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalModifierSujet")
          .classList.remove("d-block");
      });
  });
}

const modalCancelButtonModifierSujet = document.getElementById(
  "modalCancelButtonModifierSujet"
);

modalCancelButtonModifierSujet.addEventListener("click", function () {
  document.querySelector("#modalModifierSujet").classList.remove("d-block");
});

// Ressource

const createRessourceBtn = document.querySelector("#create-ressource");
const modalCancelButtonCreateRessource = document.getElementById(
  "modalCancelButtonCreateRessource"
);

if (createRessourceBtn) {
  createRessourceBtn.addEventListener("click", function () {
    document.querySelector("#modalCreateRessource").classList.add("d-block");

    document
      .querySelector("#modal-ressource-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalCreateRessource")
          .classList.remove("d-block");
      });
  });

  modalCancelButtonCreateRessource.addEventListener("click", function () {
    document.querySelector("#modalCreateRessource").classList.remove("d-block");
  });
}

// Ressource

const addRessourceBtn = document.querySelector("#btn-add-ressource");
const modalCancelButtonAjouterRessource = document.getElementById(
  "modalCancelButtonAjouterRessource"
);

if (addRessourceBtn) {
  addRessourceBtn.addEventListener("click", function () {
    document.querySelector("#modalAjouterRessource").classList.add("d-block");

    document
      .querySelector("#modal-ressource-add-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalAjouterRessource")
          .classList.remove("d-block");
      });
  });
}

document
  .getElementById("selectFileButtonRessource")
  .addEventListener("click", function () {
    document.getElementById("fileInputRessource").click();
  });

const modalDepotRessource = document.getElementById("modalCreateRessource");
const dropRessourceFileCancelButton = document.getElementById(
  "depotCancelButtonRessource"
);

modalCancelButtonAjouterRessource.addEventListener("click", function () {
  document.querySelector("#modalAjouterRessource").classList.remove("d-block");
});

if (dropRessourceFileCancelButton) {
  dropRessourceFileCancelButton.addEventListener("click", function () {
    modalDepotRessource.classList.remove("d-block");
  });
}

const listRessourceButtons = document.querySelectorAll(
  '[class*="ressourcedrop-"]'
);

const idSaeDepotRessource = document.getElementById("idSaeDepotRessource");

listRessourceButtons.forEach((element) => {
  const matches = element.className.match(/ressourcedrop-(\d+)/);
  if (matches) {
    const number = matches[1];
    element.addEventListener("click", function () {
      idSaeDepotRessource.value = number;
      modalDepotRessource.classList.add("d-block");
    });
  }
});

/**
 * Gestion des créations de groupes
 */

const addEtudiantButton = document.querySelector("#addEtudiantField");
const etudiantsContainer = document.querySelector("#etudiants-container");

if (addEtudiantButton != null) {
  addEtudiantButton.addEventListener("click", function () {
    const firstSelect = document.querySelector("#addEtudiant");
    if (firstSelect) {
      const optionsHTML = firstSelect.innerHTML;
      const newField = document.createElement("div");
      newField.className = "etudiant-container mb-3 d-flex";
      newField.innerHTML = `
        <select name="etudiants[]" class="form-select me-2 w-25">
          ${optionsHTML}
        </select>
        <button type="button" onclick="removeField(this)" class="btn btn-danger">Supprimer</button>
        `;
      etudiantsContainer.appendChild(newField);
    }
  });
}

function removeField(button) {
  const field = button.parentNode;
  field.remove();
}

const searchInput = document.getElementById("searchInput");
const checkboxes = document.querySelectorAll(".form-check.student");
const dropdownButton = document.querySelector(".dropdown-toggle");
const dropdownContent = document.getElementById("dropdownContent");
const errorMessage = document.getElementById("errorMessage");
const form = document.getElementById("studentForm");

if (dropdownButton != null) {
  dropdownButton.addEventListener("click", function () {
    const isVisible = dropdownContent.style.display === "block";
    dropdownContent.style.display = isVisible ? "none" : "block";
    if (!isVisible) searchInput.focus();
  });

  function updateDropdownButton() {
    const selected = Array.from(checkboxes)
      .filter((checkbox) => checkbox.querySelector("input").checked)
      .map((checkbox) => checkbox.querySelector("label").textContent.trim());

    dropdownButton.textContent =
      selected.length > 0 ? selected.join(", ") : "Rechercher des étudiants";
  }

  searchInput.addEventListener("input", () => {
    const filter = searchInput.value.toLowerCase();
    checkboxes.forEach((checkbox) => {
      const label = checkbox.querySelector("label").textContent.toLowerCase();
      checkbox.style.display = label.includes(filter) ? "block" : "none";
    });
  });

  checkboxes.forEach((checkbox) => {
    checkbox.querySelector("input").addEventListener("change", () => {
      updateDropdownButton();
    });
  });

  document.addEventListener("click", function (event) {
    const isClickInside =
      dropdownButton.contains(event.target) ||
      dropdownContent.contains(event.target);
    if (!isClickInside) {
      dropdownContent.style.display = "none";
    }
  });
}

/**
 * Pour ajouter jurySoutenance
 */
const searchInputProf = document.getElementById('searchInput-profs');
const checkboxesProf = document.querySelectorAll('.form-check-profs');
const dropdownButtonProf = document.querySelector('.dropdown-toggle-profs');
const dropdownContentProf = document.getElementById('dropdownContent-profs');
const errorMessageProf = document.getElementById('errorMessage-profs');

dropdownButtonProf.addEventListener('click', function () {
  const isVisible = dropdownContentProf.style.display === 'block';
  dropdownContentProf.style.display = isVisible ? 'none' : 'block';
  if (!isVisible) searchInputProf.focus();
});

function updateDropdownButtonProf() {
  const selected = Array.from(checkboxesProf)
      .filter(checkbox => checkbox.querySelector('input').checked)
      .map(checkbox => checkbox.querySelector('label').textContent.trim());

  dropdownButtonProf.textContent = selected.length > 0 ? selected.join(', ') : 'Rechercher des professeurs';
}

searchInputProf.addEventListener('input', () => {
  const filter = searchInputProf.value.toLowerCase();
  checkboxesProf.forEach((checkbox) => {
    const label = checkbox.querySelector('label').textContent.toLowerCase();
    checkbox.style.display = label.includes(filter) ? 'block' : 'none';
  });
});

checkboxesProf.forEach(checkbox => {
  checkbox.querySelector('input').addEventListener('change', () => {
    updateDropdownButtonProf();
  });
});

document.addEventListener('click', function (event) {
  const isClickInside = dropdownButtonProf.contains(event.target) || dropdownContentProf.contains(event.target);
  if (!isClickInside) {
    dropdownContentProf.style.display = 'none'; // Correction ici
  }
});