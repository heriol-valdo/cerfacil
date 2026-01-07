<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include __DIR__ . '/../elements/header.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';
require_once __DIR__ . '/../../controller/Assistance/demande-assistanceController.php';
require_once __DIR__ . '/../../controller/User/validTokenController.php';
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Demande d'assistance | ErpFacil</title>
  <link rel="stylesheet" href="../../assets/style/assistanceStyle.css" />
  <link rel="stylesheet" href="../../assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../assets/style/cardStyle.css" />
  <link rel="stylesheet" href="../../assets/style/tableStyle.css" />
  <link rel="stylesheet" href="../../assets/style/profilstyle.css" />

  <!-- Google Font Link for Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>

  <div class="content-container">

    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title">
          <h1>Demande d'assistance</h1>
        </div>
        <div class="back-icon-container">
            <a href="assistance.php"><i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i></a>
        </div>
      </div>
    </header>
    
    <main>
      <div class="wrapper1">
        <header>Envoyez votre demande</header>
        <form action="#" method="POST">
          <div class="dbl-field">
            <div class="field">
              <input type="text" name="objet" placeholder="Entrer l'objet" required>
            </div>
            <div class="field">
              <input type="tel" id="phone" name="telephone" placeholder="Numero téléphone" />
            </div>
          </div>

          <div class="message">
            <textarea placeholder="Ecrivez vous message" name="description" required></textarea>
          </div>
          <div class="button-area">
            <input class="button" type="submit" name="submit" value="Envoyer">
            <!--a class="button " href="assistance.php">Retour</a-->
            <span></span>
          </div>
        </form>
      </div>



    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>
</body>