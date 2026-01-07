<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../elements/header.php';
require_once __DIR__ . "/../../controller/Formation/formations&sessionsController.php";
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/Formateur/listFormateurByCentre.php';
require_once __DIR__ . '/../../controller/Sessions/ListSessionsController.php';
require_once __DIR__ . '/../../controller/Formation/ListFormationController.php';
require_once __DIR__ . '/../../controller/Formation/listFormationByCentre.php';
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';

$formations = [];
$sessions = [];

if (empty($allFormationsSessions)) {
    $noFormationsMessage = "Aucune formation pour ce centre";
} else {
    foreach ($allFormationsSessions as $formation) {
        $formations[] = ["id" => $formation->id, "name" => $formation->nom];
    }

    foreach ($allFormationsSessions as $formation) {
        $sessions[$formation->nom] = $formation->sessions;
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de formation XYZ</title>
    <link rel="stylesheet" href="../../assets/style/formations&sessions.css">
    <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title">
                    <h1>Formations et sessions</h1>
                </div>
            </div>
            <h2 id="centreName"><?php echo $_GET["centreName"] ?></h2>
            <div class="back-icon-container">
                <a href="../centre-formation/centre-details.php?centreId=<?= $_GET["centreId"] ?>"><i
                        class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i></a>

            </div>

        </header>
        <main>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <div>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModalFormation">Ajouter une formation</button>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSessionModal">Ajouter une
                        session</button>
                </div>
            </div>
            <div class="flex-container">
                <div class="main-content">
                    <nav>
                        <h2 class="underline">Sélectionner une formation</h2>

                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Rechercher une formation...">

                        </div>
                        <div class="formation-buttons-container">

                            <div class="formation-buttons">

                                <?php if (isset($noFormationsMessage)): ?>

                                    <p><?= $noFormationsMessage ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="pagination">

                            <button id="prevPage" disabled>Précédent</button>

                            <button id="nextPage">Suivant</button>
                        </div>

                    </nav>

                    <div class="session-container">

                        <h2 class="underline">Sessions de la formation</h2>
                        <div class="filter-container">
                            <div class="select-wrapper">
                                <select id="session-filter" class="session-filter">
                                    <option value="en-cours">En cours</option>
                                    <option value="termine">Terminées</option>
                                    <option value="a-venir">À venir</option>
                                </select>
                            </div>
                        </div>
                        <div id="session-list"></div>
                    </div>

                </div>
            </div>
            <?php include __DIR__ . '/../../view/session/add-session.php'; ?>
            <?php include __DIR__ . '/../../view/formation/add-formation.php'; ?>
            <?php include __DIR__ . '/../../view/formateur/add-formateur.php'; ?>

        </main>
        <footer>
            <?php

            include __DIR__ . '/../elements/footer.php';
            ?>

        </footer>

    </div>
    <script>
         var centreId =  "<?php echo $_SESSION['user']['role'] == 1 ? $_GET['centreId'] : $_SESSION['user']['centre']; ?>";
         var centreName =  "<?php echo isset($_GET['centreName']) ? $_GET['centreName'] : ''; ?>";
    </script>
    <script>
        var sessionFilter = document.getElementById('session-filter');
        var sessionFilterValue = sessionFilter.value;

        var sessions = <?= json_encode($sessions) ?>;
        var formations = <?= json_encode($formations) ?>;
    </script>
    <script src="../../assets/script/formations&sessions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
     <!-- Script showToast -->
     <script src="../../assets/script/toast.js"></script>

</body>

</html>