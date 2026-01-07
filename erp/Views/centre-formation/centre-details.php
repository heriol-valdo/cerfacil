<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/CentreFormation/centreDetails.php';
include __DIR__ . '/../elements/header.php';

$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Détails du centre LGX CAMPUS | ErpFacil</title>
  <link rel="stylesheet" href="../../assets/style/centreDetails.css">
  <link rel="stylesheet" href="../../assets/style/cardStyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title"><h1>Détails du centre</h1></div>
      </div>
      <div class="back-icon-container">
          <a href="../centre-formation/list-centre-formation.php">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
    </header>
    <main>
      <section class="center-details"></section>
      <div class="flex">
        <h2><?php echo $centreDetails->data->dataCentre[0]->nomCentre ?></h2>
    
      </div>


      <div class="centre-details-section">
        <h3>Informations du centre</h3>
        <p><strong>Nom du centre</strong> <?php echo $centreDetails->data->dataCentre[0]->nomCentre; ?></p>
        <p><strong>Ville</strong> <?php echo $centreDetails->data->dataCentre[0]->villeCentre ?? '' ?></p>
        <p><strong>Code Postal</strong> <?php echo $centreDetails->data->dataCentre[0]->codePostalCentre ?? '' ?></p>
        <p><strong>Adresse</strong> <?php echo $centreDetails->data->dataCentre[0]->adresseCentre ?? '' ?></p>
        <p><strong>Téléphone</strong> <?php echo $centreDetails->data->dataCentre[0]->telephoneCentre ?? ''?></p>
      </div>
      <div class="centre-details-section">
        <h3>Informations de l'entreprise</h3>
        <p><strong>Nom du dirigeant</strong> <?php echo $centreDetails->data->dataEntreprise[0]->nomDirecteur ?></p>
        <p><strong>Forme juridique</strong> <?php echo $centreDetails->data->dataEntreprise[0]->formeJuridique ?></p>
        <p><strong>Contact</strong> <?php echo $centreDetails->data->dataEntreprise[0]->email ?? '' ?></p>
        <p><strong>Site web</strong>  <?php if (!empty($siteWeb)) {
            echo '<a href="' . htmlspecialchars($siteWeb, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($siteWeb, ENT_QUOTES, 'UTF-8') . '</a></p>';
          } else {
            echo '</p>';
          }; ?>
        <p><strong>Fax</strong> <?php echo $centreDetails->data->dataEntreprise[0]->fax ?? ''; ?></p>
      </div>
      <div class="centre-details-section" id="centre-details-additionnal-access">
        <?php
        include __DIR__ . '/../elements/card.php';
        
          // Générer les composants
          $cards = array(

            array('name' => 'Salles et équipements', 'icon' => 'fa-solid fa-chalkboard', 'href' => '../salle/salles&equipements?centreId=' . $centreDetails->data->dataCentre[0]->centreId . "&centreName=" . urlencode($centreDetails->data->dataCentre[0]->nomCentre)),
            array('name' => 'Formations et sessions', 'icon' => 'fa-solid fa-graduation-cap', 'href' => '../formation/formations&sessions?centreId=' . $centreDetails->data->dataCentre[0]->centreId . "&centreName=" . urlencode($centreDetails->data->dataCentre[0]->nomCentre)),
            array('name' => 'Utilisateurs', 'icon' => 'fa-solid fa-users', 'href' => 'centre-list-user?centreId=' . $centreDetails->data->dataCentre[0]->centreId . "&centreName=" . urlencode($centreDetails->data->dataCentre[0]->nomCentre)),
            array('name' => 'Absences', 'icon' => 'fa-solid fa-calendar-times', 'href' => '../absences/absencesBySessions?centreId=' . $centreDetails->data->dataCentre[0]->centreId . "&centreName=" . urlencode($centreDetails->data->dataCentre[0]->nomCentre)),
            array('name' => 'Planning', 'icon' => 'fa-solid fa-calendar', 'href' => '../event/planning?centreId=' . $centreDetails->data->dataCentre[0]->centreId),
            array('name' => 'cerfas', 'icon' => 'fa-solid fa-file-invoice', 'href' => '../clientCerfa/details-cerfas-centres.php?centreId=' . $centreDetails->data->dataCentre[0]->centreId . "&centreName=" . urlencode($centreDetails->data->dataCentre[0]->nomCentre))

          );
          foreach($cards as $card) {
            echo generateCard($card['icon'], $card['name'], $card['href'], 'voir tout >');
          }

          ?>
      </div>
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>

</body>
</html>