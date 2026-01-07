<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__.'/../elements/header.php';
include __DIR__."/../../controller/Absences/absences&sessionsController.php";
require_once __DIR__."/../../controller/User/validTokenController.php";
require_once __DIR__ .'/../../requestFile/authRequet.php';

if (!empty($allSessionsAbsences)) {
    $sessions = array_map(function($session) {
        return [
            'id' => $session->id,
            'name' => $session->nomSession
        ];
    }, $allSessionsAbsences->sessions);

    if ($allSessionsAbsences->absences !== null) {
        $absences = [];
        foreach ($allSessionsAbsences->absences as $sessionName => $sessionAbsences) {
            $absences[$sessionName] = [];
            if (is_array($sessionAbsences)) {
                foreach ($sessionAbsences as $item) {
                    $absences[$sessionName][] = [
                        "id" => $item->id,
                        "dateDebut" => $item->dateDebut,
                        "dateFin" => $item->dateFin,
                        "raison" => $item->raison,
                        "justificatif" => $item->justificatif,
                        "id_etudiants" => $item->id_etudiants,
                        "dateCreation" => $item->dateCreation,
                        "etudiant_prenom" => $item->etudiant_prenom,
                        "etudiant_nom" => $item->etudiant_nom
                    ];
                }
            }
        }
    }
} else {
    $sessions = [];
    $noSessionsMessage = "Aucune session pour ce centre";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de formation XYZ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../assets/style/sessions&absences.css">
    <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
</head>
<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title"><h1>Sessions et Absences</h1></div>
            </div>
            <h2 id="centreName"><?php echo $_GET["centreName"] ?></h2>
            <div class="back-icon-container">
                <a href="../centre-formation/centre-details.php?centreId=<?= $_GET["centreId"] ?>"><i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i></a>
            </div>
        </header>
        <main>
           
            <div class="flex-container">
                <div class="main-content">
                    <nav>
                        <h2 class="underline">Sélectionner une session</h2>
                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Rechercher une session...">
                        </div>
                        <div class="session-buttons-container">
                            <div class="session-buttons">
                                <?php if (!empty($sessions)): ?>
                                    <?php foreach ($sessions as $session): ?>
                                        <button class="session-button" data-session-id="<?= $session['id'] ?>"><?= $session['name'] ?></button>
                                    <?php endforeach; ?>
                                <?php else: ?>
                            <p><?= $noSessionsMessage ?></p>
                            <?php endif; ?>
                            </div>
                        </div>
                        <div class="pagination">
                            <button id="prevPage" disabled>Précédent</button>
                            <button id="nextPage">Suivant</button>
                        </div>
                    </nav>
                    <div class="absences-container">
                        <h2 class="underline">Absences de la session</h2>
                        <div class="period-selector">
                            <label for="period-select">Trier par période :</label>
                            <select id="period-select">
                                <option value="all">Toutes les périodes</option>
                                <option value="thisWeek">Cette semaine</option>
                                <option value="thisMonth">Ce mois</option>
                                <option value="lastMonth">Le mois dernier</option>
                                <option value="thisYear">Cette année</option>
                            </select>
                        </div>
                        <div id="absences-list">
                        
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div id="justificatifModal" class="modal">
            <div class="modal-content">
                <span class="closeModal">&times;</span>
                <div id="justificatifContent"></div>
            </div>
    </div>
        <footer>
            <?php
                include __DIR__.'/../elements/footer.php';
            ?>
        </footer>
    </div>
    
    <script>
        var allSessionsAbsences = <?= json_encode($sessions) ?>;
        var absences = <?= json_encode($absences) ?>;
    </script>
    <script src="../../assets/script/absences&sessions.js"></script>
</body>
</html>