// Connexion au serveur WebSocket
var conn = new WebSocket("ws://localhost:8080/chat");

// Quand un message est reçu
conn.onmessage = function (event) {
  var data = JSON.parse(event.data); // Décoder le message JSON
  var pseudo = data.pseudo || "Anonyme";
  var message = data.message;
  displayMessage(pseudo + ": " + message); // Afficher avec le pseudo
};

// Fonction pour afficher les messages
function displayMessage(message) {
  var messageBox = document.getElementById("messageBox");
  var newMessage = document.createElement("div");
  newMessage.textContent = message;
  newMessage.style.padding = "8px";
  newMessage.style.marginBottom = "8px";
  newMessage.style.backgroundColor = "#f1f1f1";
  newMessage.style.borderRadius = "5px";
  newMessage.style.boxShadow = "0px 2px 5px rgba(0, 0, 0, 0.1)";
  messageBox.appendChild(newMessage);
  messageBox.scrollTop = messageBox.scrollHeight; // Scroll vers le bas
}

// Fonction pour envoyer un message
document.getElementById("sendMessage").onclick = function () {
  var messageInput = document.getElementById("messageInput");
  var pseudoInput = "test";
  var message = messageInput.value;
  var pseudo = pseudoInput.value || "Anonyme"; // Utiliser "Anonyme" si aucun pseudo n'est fourni

  if (message) {
    // Envoyer le message et le pseudo au serveur WebSocket
    conn.send(JSON.stringify({ pseudo: pseudo, message: message }));

    // Afficher immédiatement le message envoyé dans le chat
    displayMessage(pseudo + ": " + message);

    // Effacer le champ de saisie
    messageInput.value = "";
  }
};

// Envoi automatique lors de la touche "Enter"
document
  .getElementById("messageInput")
  .addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      document.getElementById("sendMessage").click();
    }
  });
