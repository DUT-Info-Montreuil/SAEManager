// Search bar

document.getElementById("search-bar").addEventListener("input", function () {
  const filter = this.value.toLowerCase().trim();
  const resources = document.querySelectorAll(".resource-item");

  resources.forEach((resource) => {
    const resourceName = resource.getAttribute("data-name").toLowerCase();
    if (resourceName.includes(filter)) {
      resource.classList.remove("d-none");
      console.log(resource.style);
    } else {
      resource.classList.add("d-none");
    }
  });

  // Recherche en direct
  document.getElementById("search-bar").addEventListener("input", function () {
    var filter = this.value.toLowerCase();
    var resources = document.querySelectorAll(".resource-item");
    resources.forEach(function (resource) {
      var name = resource
        .querySelector(".resource-name")
        .textContent.toLowerCase();
      resource.style.display = name.includes(filter) ? "" : "none";
    });
  });
});

// Tri alphabétique avec alternance A-Z / Z-A
let isAscending = true; // Variable pour suivre l'état du tri (true = A-Z, false = Z-A)

document.getElementById("sort-button").addEventListener("click", function () {
  var list = document.getElementById("ressources-list");
  var resources = Array.from(list.children);

  // Trier les éléments selon l'ordre actuel (A-Z ou Z-A)
  resources.sort(function (a, b) {
    var nameA = a.getAttribute("data-name").toLowerCase();
    var nameB = b.getAttribute("data-name").toLowerCase();

    if (isAscending) {
      return nameA.localeCompare(nameB); // Tri A-Z
    } else {
      return nameB.localeCompare(nameA); // Tri Z-A
    }
  });

  // Réorganiser les éléments dans le DOM
  resources.forEach(function (resource) {
    list.appendChild(resource);
  });

  // Inverser l'état du tri (A-Z <-> Z-A)
  isAscending = !isAscending;
});
