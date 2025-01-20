<?php

require_once 'GenericView.php';

class DashboardView extends GenericView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initDashboardPage($listeRendu, $listeSoutenance, $notifications, $nomUtilisateur)
    {
        $texteRendu = $_SESSION['estProfUtilisateur'] == 1 ? "Vous êtes associés à ces évaluations de rendu" : "Vos rendus non déposé";
        $texteSoutenance = $_SESSION['estProfUtilisateur'] == 1 ?"Vous êtes associés à ces évaluations de soutenance" : "Vos prochaines soutenances";
        echo <<<HTML
                <div class="container mt-5">
                    <h1 class="fw-bold">DASHBOARD</h1>
                    <div class="card shadow bg-white rounded">
                        <div class="d-flex flex-column align-items-center">
                            <!-- Message de bienvenue centré -->
                            <div class="mt-3 mb-4">
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
                                            $messageSoutenance = $_SESSION['estProfUtilisateur'] == 1 ? "Vous n'avez aucuns rendus à évaluer" : "Vous n'avez aucuns rendus à rendre."; 
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
                                                <p class="mb-0">Vous n'avez aucunes soutenances prévues.</p>
HTML;
                                        }
                                        else{
        echo <<<HTML
                                            <div class="bg-light border rounded overflow-auto p-3" style="max-height: 400px;">
HTML;

                                            foreach($listeSoutenance as $soutenance){
                                                $this->initLineSoutenance($soutenance['titre'], $soutenance['idSAE'], $soutenance['nomSae'], $soutenance['date']);
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
                                                <p class="mb-0">Vous n'avez aucunes notifications.</p>
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
        $dateToCheck = date('Y-m-d H:i:s');

        $dateTime = new DateTime($dateLimite);
        $dateTimeToCheck = new DateTime($dateToCheck);

        if ($dateTimeToCheck < $dateTime)
            $color = "success";
        else if ($dateTimeToCheck > $dateTime && $dateTimeToCheck < (clone $dateTime)->modify('+24 hours'))
            $color = "warning";
        else
            $color =  "danger";


        echo <<<HTML
                            <p class="mb-0 text-$color">à déposer avant le : $dateLimite</p>
                        </div>
                        <form method="POST" action="index.php?module=sae&action=details&id=$idSAE" class="w-25">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">$nomSae</button>
                        </form>
                    </div>      
            
        HTML;
    }

    function initLineSoutenance($soutenanceNom, $idSAE, $nomSae, $date){
        $nomSae = strlen($nomSae) > 30 ? substr($nomSae, 0, 30) . "..." : $nomSae;
        echo <<<HTML
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-3">
                        <div class="d-flex flex-column">
                            <p class="mb-0 fw-bold">$soutenanceNom</p>
                            <p class="mb-0">Date de votre passage : $date</p>
                        </div>
                        <form method="POST" action="index.php?module=sae&action=details&id=$idSAE" class="w-25">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">$nomSae</button>
                        </form> 
                    </div>            
            
        HTML;
    }

    private function initLineNotif($idNotification, $message, $lienForm, $date)
    {
        echo <<<HTML
                    <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3 shadow-sm mb-3">
                        <div class="d-flex flex-column">
                            <p class="mb-0 fw-bold">$date</p>
                            <p class="mb-0">$message</p>
                        </div>
                        <form method="POST" action="$lienForm" class="w-25">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">Voir</button>
                        </form> 
                        <form method="POST" action="index.php?module=dashboard&action=suprimmernotif">
                            <button type="submit" class="btn btn-danger btn-sm">X</button>
                            <input type="hidden" id="idNotification" name="idNotification" value=$idNotification>
                        </form>
                    </div>            
            
        HTML;
    }


}
