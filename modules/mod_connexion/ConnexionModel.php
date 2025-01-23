
<?php

require_once "Connexion.php";

class ConnexionModel extends Connexion
{
    public function __construct()
    {
        self::initConnexion();
    }

    function essaieConnexion()
    {
        $sql = self::$bdd->prepare('SELECT * from Personne WHERE login = ?');
        $sql->execute([$_POST['login']]);

        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }

        if ($result && $_POST['login'] === $result['login'] && password_verify($_POST['password'], $result['password']) && protectionCSRF::validerToken($_POST['token_csrf'])) {
            $_SESSION['loginUtilisateur'] = $result['login'];
            $_SESSION['idUtilisateur'] = $result['idPersonne'];
            $_SESSION['estProfUtilisateur'] = $result['estProf'];
            $_SESSION['estAdmin'] = $result['estAdmin'];
            return true;
        } else {
            return false;
        }
    }

    function inscription($nom, $prenom, $email, $password)
    {
        if (!isset($_POST['password'], $_POST['token_csrf'])) {
            return false;
        }

        if (!protectionCSRF::validerToken($_POST['token_csrf'])) {
            return false;
        }

        $baseLogin = strtolower($prenom . '.' . $nom);
        $login = $baseLogin;

        $counter = 1;
        while (true) {
            $sql = self::$bdd->prepare('SELECT * FROM Personne WHERE login = ?');
            $sql->execute([$login]);
            $result = $sql->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                break;
            }

            $login = $baseLogin . $counter;
            $counter++;
        }

        $sql = self::$bdd->prepare('SELECT * FROM Personne WHERE email = ?');
        $sql->execute([$email]);
        $emailResult = $sql->fetch(PDO::FETCH_ASSOC);

        if (!empty($emailResult)) {
            throw new Exception("L'email est déjà utilisé.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = self::$bdd->prepare('INSERT INTO Personne (nom, prenom, photoDeProfil, password, login, email, estProf) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $success = $sql->execute([$nom, $prenom, '67924ce223934-default-profile.png', $hashedPassword, $login, $email, 0]);

        return $success;
    }


    function deconnexion()
    {
        if (isset($_SESSION['loginUtilisateur'])) {
            unset($_SESSION['loginUtilisateur']);
            protectionCSRF::supprimerToken();
            return true;
        }
        return false;
    }

    public function getLoginByEmail($email)
    {
        $query = "SELECT login FROM Personne WHERE email = :email";
        $stmt = self::$bdd->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['login'] : null;
    }
}


?>