document.querySelectorAll(".toast").forEach((toastEl) => {
  toastEl.classList.add("show");

  setTimeout(() => {
    toastEl.classList.remove("show");
  }, 5000);

  toastEl.querySelector(".btn-close").addEventListener("click", () => {
    toastEl.classList.remove("show");
  });
});
