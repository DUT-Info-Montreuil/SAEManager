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
