<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAE Manager</title>
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sae-manager.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body id="background-desature">
    <!-- Page de chargement -->
    <div id="loading-screen" class="loading-screen">
        <div class="loader-container">
            <div class="loader"></div>
            <p class="loading-text">Chargement...</p>
        </div>
    </div>

    <header><?php echo $menu->getAffichage(); ?></header>

    <main><?= $module ?></main>

    <script src="js/loading.js"></script>

</body>

</html>
