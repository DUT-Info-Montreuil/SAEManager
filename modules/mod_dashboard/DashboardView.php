<?php

require_once 'GenericView.php';

class DashboardView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initDashboardPage($listeRendu, $listeSoutenance, $notifications, $nomUtilisateur, $photoDeProfil)
    {
        $toast = "";
        if (isset($_SESSION['connexion_reussie']) && $_SESSION['connexion_reussie'] === true) {
            $toast = <<<HTML
    <div class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Connexion réussie !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
HTML;
            unset($_SESSION['connexion_reussie']);
        }

        $photo = $photoDeProfil[0]['photoDeProfil'];

        $estProfUtilisateur = $_SESSION["estProfUtilisateur"] == 1;
        $texteRendu = $_SESSION['estProfUtilisateur'] == 1 ? "Vous êtes associés à ces évaluations de rendu" : "Vos rendus non déposés";
        $texteSoutenance = $_SESSION['estProfUtilisateur'] == 1 ?"Vous êtes associés à ces évaluations de soutenance" : "Vos prochaines soutenances";
        echo <<<HTML
                <div class="container mt-5 h-100">
                    <h1 class="fw-bold">DASHBOARD</h1>
                    <div class="card-general shadow bg-white rounded">
                        <div class="d-flex flex-column align-items-center">
                            <!-- Message de bienvenue centré -->
                            <div class="mt-3 mb-4 d-flex align-items-center">
                                <form id="profileForm" action="index.php?module=dashboard&action=uploadProfile" method="POST" enctype="multipart/form-data">
                                    <label for="profileImage" style="cursor: pointer;">
                                        <img src="http://saemanager-api.atwebpages.com/api/api.php?file=$photo" alt="Photo de $nomUtilisateur" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    </label>
                                    <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;" onchange="document.getElementById('profileForm').submit();">
                                </form>
                                <h2 class="fw-bold">Bienvenue $nomUtilisateur</h2>
                            </div>
                            <!-- Contenu des listes -->
                            <div class="container-fluid d-flex flex-column">
                                <div class="row g-4 m-4">
                                    <!-- Colonne 1 -->
                                    <div class="col-md-6">
                                        <h2 class="h5 mb-3">$texteRendu</h2>
HTML;
                                        if(count($listeRendu)==0){
                                            $messageSoutenance = $estProfUtilisateur ? "Vous n'avez aucuns rendus à évaluer" : "Vous n'avez aucuns rendus à rendre.";
            echo <<<HTML
                                                            <p class="mb-0">$messageSoutenance</p>
HTML;
                                        }
                                        else{
            echo <<<HTML
                                            <div class="bg-light border rounded overflow-auto p-3" style="max-height: 400px;">
HTML;
                                            foreach($listeRendu as $rendu){
                                                $this->initLineRendu($rendu['nom'], $rendu['idSAE'], $rendu['dateLimite'], $rendu['nomSae']);
                                            }
            echo <<<HTML
                                            </div>
HTML;
                                        }
            echo <<<HTML
                                    </div>
                                    <!-- Colonne 2 -->
                                    <div class="col-md-6">
                                        <h2 class="h5 mb-3">$texteSoutenance</h2>
HTML;
                                        if(count($listeSoutenance)==0){
                                            echo <<<HTML
                                                <p class="mb-0">Vous n'avez aucune soutenances prévues.</p>
HTML;
                                        }
                                        else{
        echo <<<HTML
                                            <div class="bg-light border rounded overflow-auto p-3" style="max-height: 400px;">
HTML;

                                            foreach($listeSoutenance as $soutenance){
                                                if($_SESSION['estProfUtilisateur'] != 1)
                                                    $this->initLineSoutenance($soutenance['titre'], $soutenance['idSAE'], $soutenance['nomSae'], $soutenance['date']);
                                                else
                                                    $this->initLineSoutenance($soutenance['titre'], $soutenance['idSAE'], $soutenance['nom'], $soutenance['date']);
                                            }
        echo <<<HTML
                                        </div>
HTML;
                                        }
                                        if(count($notifications)>0)
                                            $nbNotif = count($notifications);
                                        else
                                            $nbNotif = 0;
        echo <<<HTML

                                    </div>
                                </div>
                                <div class="row g-4 m-4">
                                    <!-- Colonne 1 -->
                                    <div class="col-md-6">
                                        <h2 class="h5 mb-3">Notification(s) : $nbNotif</h2>
HTML;
        if(count($notifications)==0){
            echo <<<HTML
                                                <p class="mb-0">Vous n'avez aucune notifications.</p>
HTML;
        }
        else{
            echo <<<HTML
                                            <div class="bg-light border rounded overflow-auto p-3" style="max-height: 400px;">
HTML;
            foreach($notifications as $notification){
                $this->initLineNotif($notification['idNotification'], $notification['message'], $notification['lienForm'], $notification['date']);
            }
            echo <<<HTML
                                            </div>
HTML;
        }
        echo <<<HTML
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                $toast

HTML;


        echo <<<HTML
    <script src="js/toast.js"></script>
HTML;


        return true;
    }

    function initLineRendu($renduNom, $idSAE, $dateLimite, $nomSae){
        $nomSae = strlen($nomSae) > 30 ? substr($nomSae, 0, 30) . "..." : $nomSae;
        echo <<<HTML
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-3">
                        <div class="d-flex flex-column">
                            <p class="mb-0 fw-bold">$renduNom</p>
HTML;
        $color = $this->colorByDate($dateLimite);

        $texte = $_SESSION['estProfUtilisateur'] == 1 ? "Les élèves peuvent déposer leur rendu jusqu'au :<br>": "à déposer avant le : ";
            echo <<<HTML
                            $texte<p class="mb-0 text-$color">$dateLimite</p>
                        </div>
                        <form method="POST" action="index.php?module=sae&action=details&id=$idSAE" class="w-25">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">$nomSae</button>
                        </form>
                    </div>
HTML;
    }


     function initLineSoutenance($soutenanceNom, $idSAE, $nomSae, $date){
            $color = $this->colorByDate($date);
            $texte = $_SESSION['estProfUtilisateur'] == 1 ? "Vous êtes jury du groupe \"$nomSae\"<br>Date de passage" : "Date et heure de passage";

             if($_SESSION['estProfUtilisateur'] != 1)
                 $nomSae = strlen($nomSae) > 30 ? substr($nomSae, 0, 30) . "..." : $nomSae;
             else {
                 $nomSae = "accéder à la SAE de la soutenance";
             }
            echo <<<HTML
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-3">
                        <div class="d-flex flex-column">
                            <p class="mb-0 fw-bold">$soutenanceNom</p>
                            <p class="mb-0">$texte : </p><p  class='text-$color'>$date</p>
                        </div>
                        <form method="POST" action="index.php?module=sae&action=details&id=$idSAE" class="w-25">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">$nomSae</button>
                        </form> 
                    </div>   
            HTML;

    }

    function initLineNotif($idNotification, $message, $lienForm, $date)
    {
        echo <<<HTML
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-3">
                        <div class="d-flex flex-column">
                            <p class="mb-0 fw-bold">$date</p>
                            <p class="mb-0">$message</p>
                        </div>
                        <div class="d-flex flex-row align-items-right">
                            <form method="POST" action="$lienForm" class="w-10">
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">Voir</button>
                            </form> 
                            <form method="POST" action="index.php?module=dashboard&action=suprimmernotif">
                                <button type="submit" class="btn btn-danger btn-sm">X</button>
                                <input type="hidden" id="idNotification" name="idNotification" value=$idNotification>
                            </form>
                        </div>
                    </div>            
            
        HTML;
    }

    private function colorByDate($dateLimite)
    {
        $dateToCheck = date('Y-m-d H:i:s');

        $dateTime = new DateTime($dateLimite);
        $dateTimeToCheck = new DateTime($dateToCheck);

        if ($dateTimeToCheck < (clone $dateTime)->modify('-24 hours'))
            return "success";
        else if ($dateTimeToCheck < $dateTime)
            return "warning";
        else
            return "danger";
    }


}
