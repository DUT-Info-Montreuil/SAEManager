// Empêcher l'utilisateur de modifier la partie grisée de l'email
document.getElementById("email-part2").setAttribute("disabled", "true");

function removeAtSymbol(input) {
  // Interdire la saisie du caractère '@'
  input.value = input.value.replace(/@/g, "");
}
