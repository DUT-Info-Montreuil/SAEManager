// Search bar

const afficherSAE = document.querySelector("#filter-sae-button");

document.getElementById("search-bar").addEventListener("input", function () {
  const filter = this.value.toLowerCase().trim();
  const resources = document.querySelectorAll(".resource-item");
  resources.forEach((resource) => {
    const resourceName = resource.getAttribute("data-name").toLowerCase();
    const isMySae = resource.getAttribute("data-my-sae") === "true";

    if (isProf) {
      const showOnlyMySae = afficherSAE.classList.contains("active");
      if (resourceName.includes(filter) && (!showOnlyMySae || isMySae)) {
        resource.classList.remove("d-none");
      } else {
        resource.classList.add("d-none");
      }
    } else {
      if (resourceName.includes(filter) && isMySae) {
        resource.classList.remove("d-none");
      } else {
        resource.classList.add("d-none");
      }
    }
  });
});

let isAscending = true;

document.getElementById("sort-button").addEventListener("click", function () {
  var list = document.getElementById("ressources-list");
  var resources = Array.from(list.children);

  resources.sort(function (a, b) {
    var nameA = a.getAttribute("data-name").toLowerCase();
    var nameB = b.getAttribute("data-name").toLowerCase();

    if (isAscending) {
      return nameA.localeCompare(nameB);
    } else {
      return nameB.localeCompare(nameA);
    }
  });

  resources.forEach(function (resource) {
    list.appendChild(resource);
  });

  isAscending = !isAscending;
});

const removeRessource = document.querySelectorAll("#delete-resource");

removeRessource.forEach((x) => {
  x.addEventListener("click", (e) => {
    e.preventDefault();
  });
});

if (isProf) {
  document.addEventListener("DOMContentLoaded", () => {
    const filterSaeButton = document.getElementById("filter-sae-button");
    const resourcesList = document.getElementById("ressources-list");

    filterSaeButton.addEventListener("click", () => {
      const showMySae = filterSaeButton.classList.toggle("active");
      const resources = resourcesList.querySelectorAll(".resource-item");

      resources.forEach((resource) => {
        const isMySae = resource.getAttribute("data-my-sae") === "true";
        if (showMySae && !isMySae) {
          resource.classList.add("d-none");
        } else {
          resource.classList.remove("d-none");
        }
      });

      filterSaeButton.textContent = showMySae
        ? "Afficher toutes les SAEs"
        : "Afficher mes SAE";
    });
  });
}
