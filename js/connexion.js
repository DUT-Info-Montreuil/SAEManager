// Empêcher l'utilisateur de modifier la partie grisée de l'email

function removeAtSymbol(input) {
  // Interdire la saisie du caractère '@'
  input.value = input.value.replace(/@/g, "");
}
