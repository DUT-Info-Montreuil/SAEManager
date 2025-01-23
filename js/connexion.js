function removeAtSymbol(input) {
  // Interdire la saisie du caractère '@'
  input.value = input.value.replace(/@/g, "");
}
