document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const sideMenu = document.getElementById("side-menu");

  menuToggle.addEventListener("click", () => {
    sideMenu.classList.toggle("active");
  });

  document.addEventListener("click", (event) => {
    if (
      !sideMenu.contains(event.target) &&
      !menuToggle.contains(event.target)
    ) {
      sideMenu.classList.remove("active");
    }
  });
});

document.getElementById('selectFileButton').addEventListener('click', function() {
  document.getElementById('fileInput').click();
});

document.getElementById('fileUploadForm').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Fichier envoyé avec succès !');
  var modal = bootstrap.Modal.getInstance(document.getElementById('modalDeposerSupport'));
  modal.hide();
});


            
