<?php

require_once __DIR__ . '/../../Controller/HomeController.php';
require_once __DIR__ . '/../../Controller/AssistanceController.php';
require_once __DIR__ . '/../../requestFile/authRequet.php';


$userProfil = HomeController::getProfil();

$resultTicket = AssistanceController::getTicket();

$hasNewTicket = $resultTicket['hasNewTicket'];
$notifCount =  $resultTicket['notifCount'];





// Convertir le tableau en une chaîne JSON
$tokenJson = json_encode($_SESSION['userToken']);
$userJson = json_encode($_SESSION['user']);

// Encoder la chaîne JSON pour une URL
$encodedToken = urlencode($tokenJson);
$encodedUser = urlencode($userJson);

$baseUrl = 'https://cerfa.heriolvaldo.com/cerfa/';
$urlWithToken = $baseUrl.'?token='.$encodedToken.'&user='.$encodedUser;
?>


<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../erp/assets/image/favicon.png" sizes="32x32">
  <link rel="stylesheet" href="../../erp/assets/style/menustyle.css" />
  <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />

  <!-- Boxiocns CDN Link -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <!-- Font CDN Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/brands.min.css"
    integrity="sha512-8RxmFOVaKQe/xtg6lbscU9DU0IRhURWEuiI0tXevv+lXbAHfkpamD4VKFQRto9WgfOJDwOZ74c/s9Yesv3VvIQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/fontawesome.min.css"
    integrity="sha512-d0olNN35C6VLiulAobxYHZiXJmq+vl+BGIgAxQtD5+kqudro/xNMvv2yIHAciGHpExsIbKX3iLg+0B6d0k4+ZA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>

  document.addEventListener('DOMContentLoaded', function () {
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
        arrowParent.classList.toggle("showMenu");
      });
    }

    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    //console.log(sidebarBtn);
    let iconContainers = document.querySelectorAll('.icon-container');
    let notifDot = document.querySelector('.new-ticket-dot');

    sidebarBtn.addEventListener("click", () => {
      sidebar.classList.toggle("close");
      iconContainers.forEach(container => {
        container.classList.toggle("close");
        container.classList.toggle("open");
      });

      if (notifDot) {
        if (notifDot.style.display === 'none') {
          notifDot.style.display = 'block';
        } else {
          notifDot.style.display = 'none';
        }
      }

      iconContainers.forEach(container => {
        const submenu = container.querySelector('.sub-menu');
        //console.log(container.classList);

        if (container.classList.contains('close')) {
          container.addEventListener('mouseover', handleMouseOver);
          container.addEventListener('mouseout', handleMouseOut);

        }

        if (container.classList.contains('open')) {
          //console.log("else");
          container.removeEventListener('mouseover', handleMouseOver);
          container.removeEventListener('mouseout', handleMouseOut);

          const submenu = container.querySelector('.sub-menu');
          if (submenu) {
            submenu.style.display = '';
            submenu.style.top = '';
            submenu.style.bottom = '';
          }
        }
      });
    });

    // Initial setup
    iconContainers.forEach(container => {
        //console.log("Initial setup executed");
        container.addEventListener('mouseover', handleMouseOver);
        container.addEventListener('mouseout', handleMouseOut);
      
    });
  });

  function handleMouseOver() {
    //console.log('Mouse over handled');
    const submenu = this.querySelector('.sub-menu');

    submenu.style.display = 'block'; // Ensure submenu is visible to calculate its position
    const rect = submenu.getBoundingClientRect();
    const viewportHeight = window.innerHeight;

    if (rect.bottom > viewportHeight) {
      submenu.style.top = 'auto';
      submenu.style.bottom = '0%';
    }

    if (submenu.classList.contains("blank")) {
      submenu.style.display = "";
    }
  }

  function handleMouseOut() {
    const submenu = this.querySelector('.sub-menu');
    if (submenu) {
      submenu.style.display = 'none';
    }
  }

 
</script>


<script src="../../erp/assets/JS/script.js"></script>
</head>


<!-- Commun : Profil, rechercher, accueil -->
<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">
      <a href="home">
        <img class="logo_close" src="../../erp/assets/image/favicon.png" alt="" />
      </a>
    </span>
    <span class="logo_open">
      <a href="home">
        <img src="../../erp/assets/image/favicon.png" alt="" height="60px"/>
      </a>
    </span>
  </div>
  <ul class="nav-links">
    <li class="icon-container close">

      <a href="profil">
        <div class="profile-details">
          <div class="profile-content" id="profile-picture">
            <img src="../../erp/assets/svg/user-solid.svg" alt="profileImg" class="profileImg" />
          </div>
          <div class="name-job">
            <div class="profile_name"><?= $userProfil->data->firstname ?></div>
            <div class="job"><?= $userProfil->data->role ?></div>
          </div>
          <i class='bx bx-right-arrow-alt'></i>
        </div>
      </a>
    </li>

    <li class="icon-container close">
      <a href="home">
        <i class='bx bx-home-alt'></i>
        <span class="link_name">Accueil</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="home">Accueil</a></li>
      </ul>
    </li>

    <!-- Admin content -->
    <?php if ($_SESSION['user']['role'] == 1): ?>
     
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-buildings'></i>
            <span class="link_name">Structures</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">

          <li><a class="link_name" href="#">Structures</a></li>
          <li><a href="centreFormation">Centres de formation</a></li>
          <li><a href="entreprises">Entreprises</a></li>
          <!--li><a href="../../view/salle/list-salle.php">Liste des salles</a></li>

          <li><a href="../../view/equipement/list-equipement.php">Liste des équipements</a></li>
          <li><a href="../../view/formation/list-formation.php">Liste des formations</a></li>
          <li><a href="../../view/absences/list-absences.php">Absences</a></li-->
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Utilisateurs</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="">Utilisateurs</a></li>
          <li><a href="admins">Administrateurs</a></li>
          <!-- <li><a href="../../view/financeur/list-financeur.php">Conseillers financeurs</a></li> -->
          <!-- <li><a href="../../view/etudiant/list-etudiant.php">Étudiants</a></li>
          <li><a href="../../view/formateur/list-formateur.php">Formateurs</a></li> -->
          <!-- <li><a href="../../view/gestionnaire-entreprise/list-gestionnaire-entreprise.php">Gestionnaires d'entreprise</a> -->
          <li><a href="gestionnaireCentreFormation">Gestionnaires de centre</a></li>
          <li><a href="clientCerfa">Client Cerfa</a></li>
          <li><a href="produitCerfa">Produit Cerfa</a></li>
          </li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="assistance">
          <?php if ($hasNewTicket == false): ?>
            <i class="fa-solid fa-question"></i>
          <?php else: ?>
            <i class="fa-solid fa-question"></i>
            <span class="new-ticket-dot">
              <div class="<?= $notifCount > 99 ? 'div-notif-count-3-digit' : 'div-notif-count'; ?>"><?= $notifCount; ?></div>
            </span>
          <?php endif; ?>
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="assistance">Demande d'assistance</a></li>
        </ul>
      </li>


    


      <!--------------------------------------------------------- 
    Gestionnaire Entreprise Content -->
    <?php elseif ($_SESSION['user']['role'] == 2): ?>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="../../view/event/planning.php">
            <i class='bx bx-calendar-alt'></i>
            <span class="link_name">Planning</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="../../view/event/planning.php">Planning</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Gestion des étudiants</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Gestion des étudiants</a></li>
          <li><a href="../../view/etudiant/list-etudiant.php">Liste des étudiants</a></li>
          <li><a href="../../view/absences/list-absences.php">Absences</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="#">
          <i class="fa-solid fa-book-open"></i>
          <span class="link_name">Réservation de formation</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Réservation de formation</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/assistance/assistance.php">
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/assistance/assistance.php">Demande d'assistance</a></li>
        </ul>
      </li>

      <!------------------------------------------------ 
    Gestionnaire Centre Content -->
    <?php elseif ($_SESSION['user']['role'] == 3): ?>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="../../view/event/planning.php">
            <i class='bx bx-calendar-alt'></i>
            <span class="link_name">Planning</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="../../view/event/planning.php">Planning</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Utilisateurs</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Utilisateurs</a></li>
          <li><a href="../../view/formateur/list-formateur.php">Formateurs</a></li>
          <li><a href="../../view/etudiant/list-etudiant.php">Étudiants</a></li>
          <li><a href="../../view/absences/list-absences.php">Absences</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-buildings'></i>
            <span class="link_name">Outils</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Outils</a></li>
          <li><a href="../../view/salle/list-salle.php">Liste des Salles</a></li>
          <li><a href="../../view/equipement/list-equipement.php">Liste des Equipements</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class='fa-solid fa-book'></i>
            <span class="link_name">Scolarité</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Scolarité</a></li>
          <li><a href="../../view/session/list-session.php">Sessions</a></li>
          <li><a href="../../view/formation/list-formation.php">Formations</a></li>
          <li><a href="../../view/formation-session/list-formation-session.php">Ajout d'un formateur à une session</a>
          </li>
        </ul>
      </li>

      <li class="icon-container close">
        <div class="iocn-link">
          <a href="<?php echo $urlWithToken;?>">
            <i class="bx bx-buildings"></i>
            <span class="link_name" href="<?php echo $urlWithToken;?>">CerFacil</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="<?php echo $urlWithToken;?>">CerFacil</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/assistance/assistance.php">
          <i class="fa-solid fa-question"></i>
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/assistance/assistance.php">Demande d'assistance</a></li>
        </ul>
      </li>

      <!--------------------------------------------------------------- 
    Formateur Content -->
    <?php elseif ($_SESSION['user']['role'] == 4): ?>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="../../view/event/planning.php">
            <i class='bx bx-calendar-alt'></i>
            <span class="link_name">Planning</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="../../view/event/planning.php">Planning</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="../../view/etudiant/list-etudiant.php">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Gestion des étudiants</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="../../view/etudiant/list-etudiant.php">Gestion des étudiants</a>
          </li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/absences/list-absences.php">
        <i class="fa-solid fa-users-slash"></i>
          <span class="link_name">Absences</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/absences/list-absences.php">Absences</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/assistance/assistance.php">
          <i class="fa-solid fa-question"></i>
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/assistance/assistance.php">Demande d'assistance</a></li>
        </ul>
      </li>



      <!----------------------------------------------------------- 
    Etudiant Content -->
    <?php elseif ($_SESSION['user']['role'] == 5): ?>
      <li class="icon-container close">
        <a href="../../view/event/planning.php">
          <i class='bx bx-calendar-alt'></i>
          <span class="link_name">Planning</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/event/planning.php">Planning</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/pointage/list-pointage.php">
          <i class="fa-solid fa-clock-rotate-left"></i>
          <span class="link_name">Pointages</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/pointage/list-pointage.php">Pointages</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/etudiant/documents.php">
          <i class="fa-regular fa-file header-icon"></i>
          <span class="link_name">Mes documents</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/etudiant/documents.php">Mes documents</a></li>
        </ul>
      </li>

      <!-----------------------------------------------------------
    Financeur Content -->
    <?php elseif ($_SESSION['user']['role'] == 6): ?>
      <li class="icon-container close">
        <div class="iocn-link">
          <a href="#">
            <i class="fa-solid fa-users"></i>
            <span class="link_name">Gestion des étudiants</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Gestion des étudiants</a></li>
          <li><a href="../../view/etudiant/list-etudiant.php">Liste des étudiants</a></li>
          <li><a href="../../view/absences/list-absences.php">Absences</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="#">
          <i class="fa-solid fa-book-open"></i>
          <span class="link_name">Réservation de formation</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Réservation de formation</a></li>
        </ul>
      </li>
      <li class="icon-container close">
        <a href="../../view/assistance/assistance.php">
          <i class="fa-solid fa-question"></i>
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/assistance/assistance.php">Demande d'assistance</a></li>
        </ul>
      </li>
    <?php endif; ?>
    <!-- Commun : Bouton Déconnexion -->
    <li style="margin-top: 70px; ">
      <a href="logout">
        <i class='bx bx-log-out logout'></i>
        <span class="link_name">Deconnexion</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="logout">Deconnexion</a></li>
      </ul>
    </li>
  </ul>
</div>
<section class="home-section">
  <div class="home-content">
    <i class='bx bx-menu'></i>
  </div>
</section>
</div>
