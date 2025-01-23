const editGroupeName = document.querySelector("#edit-group-name");

if (editGroupeName) {
  editGroupeName.addEventListener("click", function () {
    document.querySelector("#modalModifierNomGroupe").classList.add("d-block");

    document
      .querySelector("#modal-groupe-cancel")
      .addEventListener("click", function () {
        document
          .querySelector("#modalModifierNomGroupe")
          .classList.remove("d-block");
      });
  });
}
