<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAE Manager</title>
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sae-manager.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Page de chargement -->
    <div id="loading-screen" class="loading-screen">
        <div class="loader-container">
            <div class="loader"></div>
            <p class="loading-text">Chargement...</p>
        </div>
    </div>

    <!-- Header -->
    <header>
        <?php echo $menu->getAffichage(); ?>
    </header>

    <!-- Contenu principal -->
    <main class="flex-fill">
        <?= $module ?>
    </main>

    <!-- Footer -->
    <footer>
        <?php echo $footer->getAffichage(); ?>
    </footer>

    <script src="js/navbar/navbar.js"></script>

</body>

</html>
