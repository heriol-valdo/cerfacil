<?php

include __DIR__.'/../elements/header.php';
require_once __DIR__ . '/../../Controller/ClientCerfaController.php';

$result = ClientCerfaController::ListCerfasClientCerfa();

if ($result['error']) {
    // Afficher un message d'erreur
    echo $result['message'];
} else {
    // Utiliser les données
    $allcerfas = $result['cerfas'];
    // Traitement des données...
}


$nombresconvention = 0;
$nombresfacture = 0;

$rooms = [];


if (!empty($allcerfas)) {
    foreach ($allcerfas as $allcerfa) {

        $tableauentreprise = ClientCerfaController::ListInfoEmployeur($allcerfa->idemployeur,null);

     
        $rooms[] = [
            "id" => $allcerfa->id,
            "name" => empty($allcerfa->nomA)?$allcerfa->emailA : $allcerfa->nomA,
          
            ];

       

        if (is_array($allcerfa) || is_object($allcerfa)) {
            $roomCerfas[$allcerfa->id][] = [
                "name" => empty($allcerfa->nomA)?"Pas renseigné" : $allcerfa->nomA,
                "email" => $allcerfa->emailA,
                "telephone" => empty($allcerfa->numeroA)?"Pas renseigné" :$allcerfa->numeroA,
                "nameEntreprise" => $tableauentreprise->nomE,
                "emailEntreprise" => $tableauentreprise->emailE,
                "telephoneEntreprise" => $tableauentreprise->numeroE
            ];
        }

        if(!empty($allcerfa->conventionOpco)){
            $nombresconvention++;
        }

        if(!empty($allcerfa->factureOpco)){
            $nombresfacture++;
        }
    }

   
} else {
    $noCerfasMessage = "Aucun cerfas pour ce client";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de formation XYZ | ErpFacil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../erp/assets/style/salles&equipements.css">
</head>
<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title"><h1>Cerfas </h1></div>
               
            </div>
            <h2 id="centreName"><?php echo $_GET["centreName"] ?></h2>
            <div class="back-icon-container">
                <a href="clientCerfa"><i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i></a>
            </div>
        </header>
        <main>
    
            <div class="flex-container">
                <div class="main-content">

                  
                    
                    <nav>
                        <h2 class="underline">Sélectionner un cerfa</h2>
                        <div class="search-container">
                            <input type="text" class="search-input" placeholder="Rechercher un cerfa...">
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
                    <div class="container">
                        <div class="row">
                                <div class="col-7">
                                    <div class="equipment-container">
                                        <h2 class="underline">Details  cerfas</h2>
                                        <div id="equipment-list">
                                        <?php if (isset($noCerfasMessage)): ?>

                                        <p><?= $noCerfasMessage ?></p>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="equipment-containers">
                                        <h2 class="underline">Statistique</h2>
                                        <div id="equipment-list">
                                          <div class="equipment-item">
                                            <span class="equipment-name">Nombres de cerfas : <small>(<?=count($allcerfas)?>)</small></span>
                                          </div>

                                          <div class="equipment-item">
                                            <span class="equipment-name">Nombres de conventions : <small>(<?=$nombresconvention?>)</span>
                                          </div>

                                          <div class="equipment-item">
                                            <span class="equipment-name">Nombres de factures : <small>(<?=$nombresfacture?>)</span>
                                          </div>
                                        
                                        </div>
                                    
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <?php
                include __DIR__.'/../elements/footer.php';
            ?>
        </footer>
    </div>
    
    <script>
        var roomEquipment = <?= json_encode($roomCerfas, JSON_HEX_TAG) ?>;
        var rooms = <?= json_encode($rooms) ?>;
    </script>
    <script src="../../erp/assets/script/detailsCerfasCentres.js"></script>
</body>
</html>