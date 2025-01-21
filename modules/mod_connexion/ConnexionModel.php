
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
        if (empty($result))
            return false;

        if ($result && password_verify($_POST['password'], $result['password']) && protectionCSRF::validerToken($_POST['token_csrf'])) {
            $_SESSION['loginUtilisateur'] = $result['login'];
            $_SESSION['idUtilisateur'] = $result['idPersonne'];
            $_SESSION['estProfUtilisateur'] = $result['estProf'];
            return true;
        } else {
            return false;
        }
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
}


?>