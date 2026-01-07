<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include __DIR__.'/../elements/header.php';

    require_once __DIR__."/../../Controller/User/validTokenController.php";
    require_once __DIR__ .'/../../requestFile/authRequet.php';
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Accueil | CerFacil</title>
    <link rel="stylesheet" href="../../app/assets/style/contentContainer.css"/>
    <link rel="stylesheet" href="../../app/assets/style/cardStyle.css"/>
    <link rel="stylesheet" href="../../app/assets/style/tableStyle.css"/>
    <link rel="stylesheet" href="../../app/assets/style/profilstyle.css"/>
    <link rel="stylesheet" href="../../app/assets/style/loaderPointage.css"/>
    <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="32x32">


    <!-- Google Font Link for Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="../../app/assets/style/calendarStyle.css" />
   </head>
<body>
    <div class="content-container">
        <header>
            <div class="header-container">
                <i class="fa-regular fa-user header-icon"></i>
                <div class="title"><h1>Accueils</h1></div>
            </div>
        </header>
        <main>
            <?php require_once __DIR__ . '/../../Controller/User/homeController.php'; ?>
        </main>
        <footer>
            <?php
                include __DIR__.'/../elements/footer.php';
            ?>
        </footer>
    </div> 
</body>
