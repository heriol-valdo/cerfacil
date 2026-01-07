<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../elements/header.php';
include __DIR__ . "/../../controller/Salle/getSallesEquipementsController.php";
require_once __DIR__ . '/../../controller/Salle/listSallesByCentreController.php';
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../requestFile/authRequet.php';

$rooms = [];

if (!empty($allSallesEquipements)) {
    foreach ($allSallesEquipements as $salle) {
        $rooms[] = [
            "id" => $salle->id,
            "name" => $salle->nom,
            "equipements" => []
        ];

        if (isset($salle->equipements) && is_array($salle->equipements)) {
            foreach ($salle->equipements as $equipment) {
                if (is_object($equipment) && isset($equipment->id, $equipment->nom, $equipment->quantite, $equipment->id_salles)) {
                    $roomName = $salle->nom; 
                    if (!isset($roomEquipment[$roomName])) {
                        $roomEquipment[$roomName] = [];
                    }
                    $roomEquipment[$roomName][] = [
                        "name" => $equipment->nom,
                        "quantity" => $equipment->quantite,
                        "id" => $equipment->id,
                        "id_salles" => $equipment->id_salles
                    ];
                }
            }
        }

    }

    
} else {
    $noSallesMessage = "Aucune salle pour ce centre";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salles et équipements | ErpFacil</title>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/style/salles&equipements.css">
    <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
</head>

<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-solid fa-users header-icon"></i>
                <div class="title">
                    <h1>Salles et équipements</h1>
                </div>
                <h2 id="centreName"><?php echo $_GET["centreName"] ?></h2>
                <div class="back-icon-container">
                    <a href="../centre-formation/centre-details.php?centreId=<?= $_GET["centreId"] ?>">
                        <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
                    </a>
                </div>
            </div>
        </header>
        <main>
            <div class="d-flex justify-content-between mt-3 mb-3">
                <div>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSalleModal">Ajouter une
                        salle</button>
                    <button class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#addEquipementModal">Ajouter un équipement</button>
                </div>
            </div>
            <div class="flex-container">
                <div class="main-content">
                    <nav>
                        <h2 class="underline">Sélectionner une salle</h2>
                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Rechercher une salle...">
                        </div>
                        <div class="room-buttons-container">
                            <div class="room-buttons">

                            </div>
                        </div>
                        <div class="pagination">
                            <button id="prevPage" disabled>Précédent</button>
                            <button id="nextPage">Suivant</button>
                        </div>
                    </nav>
                    <div class="equipment-container">
                        <h2 class="underline">Équipement de la salle</h2>
                        <div id="equipment-list">
                            <?php if (isset($noSallesMessage)): ?>

                                <p><?= $noSallesMessage ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../../view/salle/add-salle.php'; ?>
            <?php include __DIR__ . '/../../view/equipement/add-equipement.php'; ?>

        </main>
        <footer>
            <?php
            include __DIR__ . '/../elements/footer.php';
            ?>
        </footer>
    </div>

    <script>
        var centreId = "<?php echo $_SESSION['user']['role'] == 1 ? $_GET['centreId'] : $_SESSION['user']['centre']; ?>";
        var centreName = "<?php echo isset($_GET['centreName']) ? $_GET['centreName'] : ''; ?>";

        var roomEquipment = <?= json_encode($roomEquipment) ?>;
        var rooms = <?= json_encode($rooms) ?>;
    </script>
    <script src="../../assets/script/salles&equipements.js"></script>
    <!-- Script showToast -->
    <script src="../../assets/script/toast.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>