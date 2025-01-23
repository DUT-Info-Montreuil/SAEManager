/**
 * Gestion du modal (pop up) "Dépot Document"
 **/

const modalDepotDocument = document.getElementById("modalDepotDocument");
const dropDocumentFileCancelButton = document.getElementById(
  "depotCancelButtonDocument"
);
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
