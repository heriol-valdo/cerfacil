<?php // Admin : home-content.php

include __DIR__.'../../elements/header.php';
// require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../Controller/HomeController.php';
require_once __DIR__ . '/../../Controller/AssistanceController.php';
// require_once __DIR__ . '/../../Controller/User/validTokenController.php';

$tableau = AssistanceController::ticket();
$ticketList = $tableau["ticketList"];
$ticketsEnCours = $tableau["ticketsEnCours"];
$ticketsHistorique = $tableau["ticketsHistorique"];




?>

<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Accueil | ErpFacil</title>
    <!-- <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css"/> -->
    <link rel="stylesheet" href="../../erp/assets/style/cardStyle.css"/>
    <link rel="stylesheet" href="../../erp/assets/style/tableStyle.css"/>
    <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css"/>
    <link rel="stylesheet" href="../../erp/assets/style/loaderPointage.css"/>
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="32x32">




    <!-- Google Font Link for Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="../../erp/assets/style/calendarStyle.css" />
    <script src="../../erp/assets/script/toast.js"></script>
   </head>
<body>
        
    
       
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title"><h1>Accueil</h1></div>
            </div>
        </header>
        <main>
        <div class="container-flex-table">
            <!-- Si la réponse est "valid" -->

            <div class="div-wrapper">
                <!-- Tickets en cours -->
                <div class="table-content-container">

                    <table class="table-duo-table">
                        <caption>Tickets en cours</caption>
                       
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">État</th>
                                    <th scope="col">Objet</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <?php if (!empty($ticketsEnCours)): ?>
                                <?php foreach ($ticketsEnCours as $ticket): ?>
                                    <?php // Formatage date à la FR
                                        $dateCreation = new DateTime($ticket->dateCreation);
                                        
                                        $dateCreation = $dateCreation->format('d-m-Y H:i'); 
                                    ?>
                                    <?php if ($ticket->etat == "En cours de traitement" || $ticket->etat == "Envoyé"): ?>

                                        <tr class="table-row-admin">
                                            <td><?= $dateCreation ?></td>
                                            <td><?= $ticket->etat ?></td>
                                            <td><?= $ticket->objet ?></td>
                                            <td>
                                                <form action="VoirDemande" method="POST">
                                                    <input type="hidden" name="ticketId" value="<?= $ticket->id ?>" />
                                                    <input id="button-ticket" type="submit" name="voirTicket" value="Voir" />
                                                </form>
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="table-row-admin">
                                    <td colspan="4">
                                        <p>Félicitations, vous êtes à jour dans vos tickets !</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                       
                        <!-- Si la réponse est "erreur" -->
                        <?php if (property_exists($ticketList, 'erreur')): ?>
                            <div class="div-wrapper">
                                <p><?= $ticketList->{'erreur'} ?></p>
                            </div>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

        


            <!-- Tickets terminés -->
            <div class="div-wrapper">
                <div class="table-content-container">
                    <table class="table-duo-table">
                        <caption>Historique des tickets</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">État</th>
                                    <th scope="col">Objet</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>

                            <?php foreach ($ticketsHistorique as $ticket): ?>
                                <?php if ($ticket->etat == "Résolu" || $ticket->etat == "Abandonné"): ?>
                                    <?php // Formatage date à la FR
                                        $dateCreation = new DateTime($ticket->dateCreation);
                                        
                                        $dateCreation = $dateCreation->format('d-m-Y H:i'); 
                                    ?>

                                    <tr class="table-row-admin">
                                        <td><?= $dateCreation ?></td>
                                        <td><?= $ticket->etat ?></td>
                                        <td><?= $ticket->objet ?></td>
                                        <td>
                                            <form action="VoirDemande" method="POST">
                                                <input type="hidden" name="ticketId" value="<?= $ticket->id ?>" />
                                                <input id="button-ticket" type="submit" name="voirTicket" value="Voir" />
                                            </form>
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            <?php endforeach; ?>
                       
                        <!-- Si la réponse est "valid-null" -->
                       
                       
                    </table>
                </div>
            </div>

        </div>
        </main>

        
        
    </div> 

   
</body>

<?php 
include __DIR__.'../../elements/footer.php';

?>