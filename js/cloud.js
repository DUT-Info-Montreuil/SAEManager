/**
 * Gestion du modal (pop up) "Dépot Document"
 **/

const modalDepotDocument = document.getElementById("modalDepotDocument");
const dropDocumentFileCancelButton = document.getElementById(
  "depotCancelButtonDocument"
);
const dropDocumentCancelButton = document.getElementById("modalCancelButtonDocument");
const listDocumentButtons = document.querySelectorAll('[class*="documentdrop-"]');
const idSaeDepotDocument = document.getElementById("idSaeDepotDocument");

document
  .getElementById("selectFileButtonDocument")
  .addEventListener("click", function () {
    document.getElementById("fileInputDocument").click();
  });

dropDocumentFileCancelButton.addEventListener("click", function () {
  modalDepotDocument.classList.remove("d-block");
});

dropDocumentCancelButton.addEventListener("click", function () {
  modalDepotDocument.classList.remove("d-block");
});

listDocumentButtons.forEach((element) => {
  const matches = element.className.match(/documentdrop-(\d+)/);
  if (matches) {
    const number = matches[1];
    element.addEventListener("click", function () {
      console.log("test");
      idSaeDepotDocument.value = number;
      modalDepotDocument.classList.add("d-block");
    });
  }
});

/**
 * Gestion du modal (pop-up) "Suprimmer Document"
 */

const modalSupressionDocument = document.getElementById(
  "modalSupressionDepotDocument"
);
const modalSupressionDocumentCancelButton = document.getElementById(
  "modalSupressionDepotDocument"
);
const modalValiderDocument = document.getElementById("modalValiderDepotDocument");

const listSupressionDocumentButtons = document.querySelectorAll(
  '[class*="supressDocumentButton"]'
);

listSupressionDocumentButtons.forEach((element) => {
  element.addEventListener("click", function () {
    matches = element.className.match(/supressDocumentButton-(\d+)/);
    document.getElementById("idDepotSupressionDocument").value = matches[1];
    modalSupressionDocument.classList.add("d-block");
  });
});
modalSupressionDocumentCancelButton.addEventListener("click", function () {
  modalSupressionDocument.classList.remove("d-block");
});
