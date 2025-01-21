<?php
require_once './vendor/autoload.php'; // Assurez-vous que le chemin est correct

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

// Implémentation du composant WebSocket
class Chat implements MessageComponentInterface
{
    // Tableau pour stocker les connexions des clients
    protected $clients;
    // Tableau pour stocker l'historique des messages
    protected $messages = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Lorsqu'un client se connecte, on l'ajoute à la liste des clients
        $this->clients->attach($conn);
        echo "Nouveau client connecté : " . $conn->resourceId . "\n";

        // Envoi de l'historique des messages au client qui vient de se connecter
        foreach ($this->messages as $message) {
            $conn->send($message);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Décoder le message JSON reçu
        $data = json_decode($msg, true);
        $pseudo = $data['pseudo'] ?? 'Anonyme'; // Récupérer le pseudo ou utiliser un pseudo par défaut
        $text = $data['message'] ?? '';

        // Construire le message avec le pseudo
        $formattedMessage = json_encode([
            'pseudo' => $pseudo,
            'message' => $text,
        ]);

        // Ajouter à l'historique
        $this->messages[] = $formattedMessage;

        // Diffuser le message à tous les autres clients
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($formattedMessage);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Lorsqu'un client se déconnecte, on le retire de la liste des clients
        $this->clients->detach($conn);
        echo "Client déconnecté : " . $conn->resourceId . "\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erreur : " . $e->getMessage() . "\n";
        $conn->close();
    }
}

// Démarrage du serveur WebSocket sur le port 8080
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Serveur démarré sur ws://localhost:8080\n";
$server->run();
