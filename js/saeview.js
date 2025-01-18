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
  document
    .getElementById("selectFileButtonSupport")
    .addEventListener("click", function () {
      document.getElementById("fileInputSupport").click();
    });
});

dropSupportFileCancelButton.addEventListener("click", function () {
  modalDepotSupport.classList.remove("d-block");
});

listSupportButtons.forEach((element) => {
  const matches = element.className.match(/supportdrop-(\d+)/);
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
console.log(listSupressionRenduButtons);
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

// Ressource

const createRessourceBtn = document.querySelector("#create-ressource");

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
}

const addRessourceBtn = document.querySelector("#btn-add-ressource");

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

// Ressource
