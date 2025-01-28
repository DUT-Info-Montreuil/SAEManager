
<?php
//Tout droit réservée
//All right reserved
//Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN

require_once 'GenericView.php';

class ConnexionView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    function deconnexionPage($msg)
    {
        echo <<<HTML
        <div class="container mt-5 h-100">
            <h1 class="fw-bold">DÉCONNEXION</h1>
            <div class="card shadow bg-white rounded w-100 h-75">
                <div class="d-flex w-100 h-75 justify-content-center m-auto">
                    $msg
                </div>
            </div>
        </div>
HTML;
    }

    function connexionPage($msg_erreur)
    {
        $toast = '';

        if (isset($_SESSION['deconnexion']) && $_SESSION['deconnexion']) {
            $toast = <<<HTML
        <div class="toast align-items-center text-bg-warning border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Déconnexion réussie !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
HTML;
            unset($_SESSION['deconnexion']);
        }

        if (isset($_SESSION['inscription_reussie']) && $_SESSION['inscription_reussie']) {
            $identifiant = $_SESSION['identifiant_inscription'];
            $toast .= <<<HTML
        <div class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Inscription réussie ! Veuillez vous connecter avec vos identifiants : $identifiant
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
HTML;
            unset($_SESSION['inscription_reussie']);
            unset($_SESSION['identifiant_inscription']);
        }

        echo <<<HTML
    <div class="container mt-5 h-100">
        <h1 class="fw-bold text-center">Connexion</h1>
        <div class="card shadow bg-white rounded mx-auto p-4 d-flex flex-column flex-md-row rounded-2 card-connexion">
            <div class="illustration-container d-flex justify-content-center align-items-center flex-column rounded-2">
                <img src="assets/img/Saly-13.png" alt="Illustration" class="img-fluid">
            </div>

            <div class="form-container d-flex flex-column justify-content-center align-items-center p-4">
                <div class="text-center mb-4">
                    <p>Bienvenue sur <span class="sae-manager-text">SAÉ MANAGER</span>, l’outil qui va te changer la vie !</p>
                </div>

                <p class="text-danger text-center mb-3">$msg_erreur</p>

                <form action="index.php?module=connexion&infoConnexion=essaieConnexion" method="POST" class="w-100">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="login" class="form-control" placeholder="Nom d'utilisateur" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                        </div>
                    </div>

HTML .
            protectionCSRF::genererTokenInput() .
            <<<HTML
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </div>
                </form>

                <!-- Lien vers l'inscription -->
                <div class="text-center mt-3">
                    <p>Vous n'avez pas de compte ? <a href="index.php?module=connexion&infoConnexion=register" class="btn btn-link">Inscrivez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
    $toast

    <script src="js/toast.js"></script>
    <footer style="background-color: #343a40; color: white; padding: 10px; text-align: center;">
        <p>
            Tout droit réservée &copy 2025 <br>
            All right reserved &copy 2025 <br>
            Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN
        </p>
    </footer>
HTML;
    }


    function inscriptionPage($msg_erreur)
    {
        echo <<<HTML
    <div class="container mt-5 h-100">
        <h1 class="fw-bold text-center">Inscription</h1>
        <div class="card shadow bg-white rounded mx-auto p-4 d-flex flex-column flex-md-row card-connexion">
            <div class="illustration-container d-flex justify-content-center align-items-center flex-column rounded-2">
                <img src="assets/img/Saly-13.png" alt="Illustration" class="img-fluid">
            </div>

            <div class="form-container d-flex flex-column justify-content-center align-items-center p-4">
                <div class="text-center mb-4">
                    <p>Bienvenue sur <span class="sae-manager-text">SAÉ MANAGER</span>, l’outil qui va te changer la vie !</p>
                </div>

                <p class="text-danger text-center mb-3">$msg_erreur</p>

                <form action="index.php?module=connexion&infoConnexion=essaieInscription" method="POST" class="w-100">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nom" class="form-control" placeholder="Nom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input 
                                type="text" 
                                name="email" 
                                class="form-control" 
                                placeholder="Email universitaire" 
                                required 
                                id="email-part1" 
                                pattern="^[^@]+$" 
                                oninput="removeAtSymbol(this)">
                            <span class="input-group-text" id="email-domain">@iut.univ-paris8.fr</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                        </div>
                    </div>

HTML .
            protectionCSRF::genererTokenInput() .
            <<<HTML
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                    </div>
                </form>

                <!-- Lien vers la connexion -->
                <div class="text-center mt-3">
                    <p>Vous avez déjà un compte ? <a href="index.php?module=connexion" class="btn btn-link">Connectez-vous</a></p>
                </div>

                <!-- Informations supplémentaires -->
                <div class="text-center mt-4">
                    <p>Vous êtes enseignant ? <span class="text-muted">Contactez le support pour être inscrit en tant qu'enseignant.</span></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/connexion.js"></script>
    <footer style="background-color: #343a40; color: white; padding: 10px; text-align: center;">
        <p>
            Tout droit réservée &copy 2025 <br>
            All right reserved &copy 2025 <br>
            Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN
        </p>
    </footer>
HTML;
    }
}
