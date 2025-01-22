document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const sideMenu = document.getElementById("side-menu");

  if (menuToggle) {
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
  }
});

window.addEventListener("load", function () {
  const loadingScreen = document.getElementById("loading-screen");

  loadingScreen.style.visibility = "visible";
  loadingScreen.style.opacity = 1;

  setTimeout(function () {
    loadingScreen.style.opacity = 0;
    setTimeout(function () {
      loadingScreen.style.visibility = "hidden";
    }, 200);
  }, 100);
});
