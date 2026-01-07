<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../controller/CentreFormation/ListCentreFormationController.php';
require_once __DIR__ . '/../../controller/Entreprise/ListEntrepriseCentreController.php';

require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Centres Des Formations | ErpFacil</title>
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../assets/style/modals.css" />
  <link rel="stylesheet" href="../../assets/style/pagination.css" />
  <link rel="stylesheet" href="../../assets/style/style.css" />
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="bx bx-buildings header-icon"></i>
        <div class="title">
          <h1>Centres de formation</h1>
        </div>
      </div>
      <div class="back-icon-container">
          <a href="../user/home.php">
            <i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i>
          </a>
        </div>
    </header>
    <main>
    <div class="card-container">
        <!-- Card 1 -->
        <div class="card">
            <h2>Centres</h2>
            <p>Voir toutes les liste des centres</p>
            <a href="../../view/centre-formation/list-centre-formation.php"><button>VOIR</button></a>
        </div>

        <!-- Card 2 -->
        <div class="card">
            <h2>Salles</h2>
            <p>Voit toutes les listes des salles</p>
            <a href="../../view/salle/list-salle.php"><button>VOIR</button></a>
        </div>

        <!-- Card 3 -->
        <div class="card">
            <h2>Equipement</h2>
            <p>Voir toutes les listes des equipéments</p>
            <a href="../../view/equipement/list-equipement.php"><button>VOIR</button></a>
        </div>


           <!-- Card 4 -->
       <div class="card">
            <h2>Formations</h2>
            <p>Voir toutes les listes des formations</p>
            <a href="../../view/formation/list-formation.php"><button>VOIR</button></a>
        </div>
    

       <!-- Card 5 -->
       <div class="card">
            <h2>Absences</h2>
            <p>Voir les absences</p>
            <a href="../../view/absences/list-absences.php"><button>VOIR</button></a>
        </div>
        
    </div>


   
      
    

    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
  </div>
</body>

</html>