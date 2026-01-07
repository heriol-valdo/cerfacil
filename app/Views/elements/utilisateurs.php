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
            <h2>Admin</h2>
            <p>Voir toutes les Admin</p>
            <a href="../../view/admin/list-admin.php"><button>VOIR</button></a>
        </div>

        <!-- Card 2 -->
        <div class="card">
            <h2>Conseillers financeurs</h2>
            <p>Voit toutes les Conseillers financeurs</p>
            <a href="../../view/financeur/list-financeur.php"><button>VOIR</button></a>
        </div>

          <!-- Card 3 -->
          <div class="card">
            <h2>Étudiants</h2>
            <p>Voit toutes les Étudiants</p>
            <a href="../../view/etudiant/list-etudiant.php"><button>VOIR</button></a>
        </div>

        <!-- Card 4 -->
        <div class="card">
            <h2>Formateurs</h2>
            <p>Voir toutes les Formateurs</p>
            <a href="../../view/formateur/list-formateur.php"><button>VOIR</button></a>
        </div>
    </div>
    <div class="card-container">
        <!-- Card 5 -->
        <div class="card">
            <h2>Gestionnaires de centre</h2>
            <p>Voir toutes les Gestionnaires de centre</p>
            <a href="../../view/gestionnaire-centre/list-gestionnaire-centre.php"><button>VOIR</button></a>
        </div>

        <!-- Card 6 -->
        <div class="card">
            <h2>Entreprises</h2>
            <p>Voit toutes les Entreprises</p>
            <a href="../../view/entreprise/list-entreprise.php"><button>VOIR</button></a>
        </div>

            <!-- Card 7 -->
            <div class="card">
                        <h2>Gestionnaires d'entreprise</h2>
                        <p>Voit toutes les Gestionnaires d'entreprise</p>
                        <a href="../../view/gestionnaire-entreprise/list-gestionnaire-entreprise.php"><button>VOIR</button></a>
                    </div>


        <!-- Card 8 -->
        <div class="card">
            <h2>Client Cerfa</h2>
            <p>Voir les classes de Client Cerfa</p>
            <a href="../../view/clientCerfa/list-clientCerfa.php"><button>VOIR</button></a>
        </div>

          <!-- Card 9 -->
          <div class="card">
            <h2>Produit Cerfa</h2>
            <p>Voir toutes les Produit Cerfa</p>
            <a href="../../view/produitCerfa/list-produitCerfa.php"><button>VOIR</button></a>
        </div>

    </div>



      


    
    </main>
    <footer>
      <?php include __DIR__ . '/../elements/footer.php'; ?>
    </footer>
</div>
</body>

</html>