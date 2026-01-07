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
  <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="../../app/assets/image/favicon.png" sizes="32x32">
  <!-- <link rel="stylesheet" href="../../app/assets/style/menustyle.css" /> -->

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


<script src="../../app/assets/JS/script.js"></script>


<style>
 /* Google Fonts Import Link */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

:root{
  --main-color:#1e293b;
  --2main-color:#ffffff;
  --3main-color:#3b82f6;
  --4main-color:#64748b;
  --hover-color:#3b82f6;
  --accent-color:#06b6d4;
  --danger-color:#ef4444;
  --success-color:#10b981;
}

*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

.sidebar{
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  min-height: 600px;
  width: 260px;
  background: linear-gradient(145deg, #1e293b 0%, #334155 100%);
  z-index: 100;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar.close{
  width: 78px;
  align-items: center;
}

.logo_close{
  max-height: 95px;
  filter: drop-shadow(0 2px 8px rgba(59, 130, 246, 0.3));
}

.sidebar .logo-details{
  max-width: 260px;
  width: 100%;
  max-height: 100%;
  height: 100px;
  display: flex;
  align-items: center;
  position: relative;
  padding-left: 15px;
  padding-top: 5px;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar .logo-details span {
  position: absolute;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar.close .logo_open {
  opacity: 0;
  transform: scale(0.8);
}

.sidebar.close .logo_name {
  opacity: 1;
  transform: scale(1);
}

.sidebar.close .logo-details i{
  font-size: 30px;
  color: #fff;
  min-width: 78px;
  text-align: center;
  line-height: 50px;
}

.sidebar.close .logo_open{
  display: none;
}

.sidebar.close .logo_name{
  display: block;
}

.logo_name{
  display: none;
  opacity: 0;
  object-fit: contain; 
}

.sidebar.close .logo-details .logo_name img{
  width: 70%; 
  align-items: center;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.sidebar.close .logo-details .logo_name img:hover{
  transform: scale(1.1);
}

.logo_open {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  opacity: 1;
  transition: all 0.3s ease;
}

.sidebar .logo-details .logo_open img {
  width: 70%;
  max-width: 70%;
  height: auto; 
  object-fit: contain;
  transition: transform 0.3s ease;
}

.sidebar .logo-details .logo_open img:hover {
  transform: scale(1.05);
}

.sidebar .nav-links{
  height: 100%;
  padding: 20px 0 150px 0;
  overflow: auto;
}

.sidebar.close .nav-links{
  overflow: visible;
}

.sidebar .nav-links::-webkit-scrollbar{
  width: 6px;
}

.sidebar .nav-links::-webkit-scrollbar-track{
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 3px;
}

.sidebar .nav-links::-webkit-scrollbar-thumb{
  background: var(--hover-color);
  border-radius: 3px;
}

.sidebar .nav-links li{
  position: relative;
  list-style: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  margin: 2px 8px 2px 0;
  border-radius: 0 25px 25px 0;
}

.sidebar .nav-links li:hover{
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  transform: translateX(8px);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.sidebar .nav-links li .logout{
  color: var(--danger-color);
  transition: all 0.3s ease;
}

.sidebar .nav-links li:hover .logout{
  color: #fff;
}

.sidebar .nav-links li .iocn-link{
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.sidebar.close .nav-links li .iocn-link{
  display: block;
}

.sidebar .nav-links .search{
  margin: 20px 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); 
  border-radius: 12px; 
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 0;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
}

.sidebar.close .nav-links .search{
  background: none;
  border: none;
  margin: 0;
}

.sidebar .nav-links input{
  background: none;
  border: none;
  color: #E4E9F7;
  padding: 12px 15px;
  width: 100%;
  border-radius: 12px;
}

.sidebar .nav-links ::placeholder{
  background: none;
  border: none;
  color: rgba(228, 233, 247, 0.7);
}

.sidebar.close .nav-links .iocn-link input{
  display: none; 
}

.sidebar .nav-links li i{
  height: 50px;
  min-width: 78px;
  text-align: center;
  line-height: 50px;
  color: #fff;
  font-size: 20px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 10px;
}

.sidebar .nav-links li:hover i{
  transform: scale(1.1);
}

.sidebar .nav-links li.showMenu i.arrow{
  transform: rotate(-180deg);
}

.sidebar.close .nav-links i.arrow{
  display: none;
}

.sidebar .nav-links li a{
  display: flex;
  align-items: center;
  text-decoration: none;
}

.sidebar .nav-links li a .link_name{
  font-size: 16px;
  font-weight: 500;
  color: #fff;
  transition: all 0.3s ease;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.sidebar.close .nav-links li a .link_name{
  opacity: 0;
  pointer-events: none;
  display: none;
}

.sidebar .nav-links li .sub-menu{
  padding: 6px 6px 6px 60px;
  margin-top: 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: none;
  border-radius: 8px;
  backdrop-filter: blur(10px);
}

.sidebar .nav-links li.showMenu .sub-menu{
  display: block;
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.sidebar .nav-links li .sub-menu a{
  color: rgba(255, 255, 255, 0.8);
  font-size: 13px;
  padding: 8px 5px;
  white-space: nowrap;
  transition: all 0.3s ease;
  border-radius: 6px;
  margin: 2px 0;
  display: block;
}

.sidebar .nav-links li .sub-menu a:hover{
  color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding-left: 15px;
}

.sidebar.close .nav-links li .sub-menu{
  position: absolute;
  left: 100%;
  top: -10px;
  margin-top: 0;
  padding: 15px 20px;
  border-radius: 12px;
  opacity: 0;
  display: block;
  pointer-events: none;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.1);
  min-width: 180px;
}

.sidebar.close .nav-links li:hover .sub-menu{
  top: 0;
  opacity: 1;
  pointer-events: auto;
  transform: translateX(5px);
}

.sidebar .nav-links li .sub-menu .link_name{
  display: none;
}

.sidebar.close .nav-links li .sub-menu .link_name{
  margin-top: 10px;
  padding: 8px 12px;
  font-size: 14px;
  opacity: 1;
  display: block;
  background: rgba(59, 130, 246, 0.1);
  border-radius: 8px;
  border-left: 3px solid var(--hover-color);
  
}

.sidebar .nav-links li .sub-menu.blank{
  opacity: 1;
  pointer-events: auto;
  padding: 10px 20px;
  opacity: 0;
  pointer-events: none;
}

.sidebar .nav-links li:hover .sub-menu.blank{
  top: 50%;
  transform: translateY(-50%) translateX(5px);
  opacity: 1;
  pointer-events: auto;
}

.sidebar .profile-details{
  cursor: pointer;
  width: 260px;
  display: flex;
  align-items: center;
  justify-content: space-around;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  margin-top: 0;
  height: 80px;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar.close .profile-details{
  background: rgba(59, 130, 246, 0.1);
  cursor: pointer;
  padding-left: 18%;
  width: 78px;
}

.sidebar .profile-details .profile-content{
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.sidebar .profile-details img{
  height: 50px;
  width: 50px;
  object-fit: contain;
  border-radius: 50%;
     background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  display: flex;
  justify-content: center; 
  align-items: center;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
  border: 2px solid rgba(255, 255, 255, 0.2);
}

.sidebar .profile-details img:hover{
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

.sidebar.close .profile-details img{
  display: flex;
  padding: 10px;
  justify-content: center;
}

.sidebar .profile-details .profile_name,
.sidebar .profile-details .job{
  color: #fff;
  font-size: 16px;
  font-weight: 500;
  white-space: nowrap;
  cursor: pointer;
  width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
  transition: all 0.3s ease;
}

.sidebar .profile-details .profile_name:hover,
.sidebar .profile-details .job:hover{
  color: var(--accent-color);
}

.hidden{
  display: none;
}

.sidebar.close .profile-details i,
.sidebar.close .profile-details .profile_name,
.sidebar.close .profile-details .job{
  display: none;
}

#profile-picture{
  display: none;
}

.sidebar.close #profile-picture{
  display: block;
}

.sidebar .profile-details .job{
  font-size: 12px;
  color: rgba(255, 255, 255, 0.8);
}

.home-section{
  position: fixed;
  top: 0px;
  left: 270px;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar.close ~ .home-section{
  left: 78px;
  width: calc(100% - 78px);
}

.home-section .home-content{
  margin-top: 20px;
  position: fixed;
  height: 60px;
  display: flex;
  align-items: center;
}

.home-section .home-content .bx-menu,
.home-section .home-content .text{
  color: #11101d;
  font-size: 35px;
}

.home-section .home-content .bx-menu{
  margin: 0 15px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.home-section .home-content .bx-menu:hover{
  color: var(--hover-color);
  transform: scale(1.1);
}

.home-section .home-content .text{
  font-size: 26px;
  font-weight: 600;
}

.name-job{
  padding-left: 15px;
  width: 100%;
}

/* Notification : Point orange amélioré */
.new-ticket-dot {
  position: absolute;
  top: 7px; 
  right: 14px;
  width: 20px;
  height: 20px;
  background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
  border-radius: 50%;
  z-index: 10; 
  display: flex;
  justify-content: center;
  align-items: center;
  animation: pulse 2s infinite;
  box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4);
}

@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4);
  }
  50% {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.6);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4);
  }
}

.div-notif-count {
  color: white;
  font-size: 0.8em;
  font-weight: bold;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

.div-notif-count-3-digit {
  color: white;
  font-size: 0.65em;
  font-weight: bold;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}



@media (max-width: 400px) {
  .sidebar.close .nav-links li .sub-menu{
    display: none;
  }
  .sidebar{
    width: 78px;
  }
  .sidebar.close{
    width: 0;
  }
  .home-section{
    left: 78px;
    width: calc(100% - 78px);
    z-index: 100;
  }
  .sidebar.close ~ .home-section{
    width: 100%;
    left: 0;
  }
}
  
</style>
</head>


<!-- Commun : Profil, rechercher, accueil -->
<div class="sidebar close">
  <div class="logo-details">
    <span class="logo_name">
      <a href="home">
        <img class="logo_close" src="../../app/assets/image/favicon.png" alt="" />
      </a>
    </span>
    <span class="logo_open">
      <a href="home">
        <img src="../../app/assets/image/favicon.png" alt="" height="60px"/>
      </a>
    </span>
  </div>
  <ul class="nav-links">
    <li class="icon-container close">

      <a href="editPassword">
        <div class="profile-details">
          <div class="profile-content" id="profile-picture">
            <img src="../../app/assets/svg/user-solid.svg" alt="profileImg" class="profileImg" />
          </div>
          <div class="name-job">
            <div class="profile_name"><?= $userProfil->data->firstname ?></div>
            <div class="job"><?= $userProfil->data->role ?></div>
          </div>
          <i class='bx bx-right-arrow-alt'></i>
        </div>
      </a>
      
      <ul class="sub-menu blank">
        <li><a class="link_name" href="editPassword">Profil</a></li>
      </ul>
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
    <?php if ($_SESSION['user']['role'] == 5): ?>
     
     
      <!-- <li class="icon-container close">
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
          <li><a href="gestionnaireCentreFormation">Gestionnaires de centre</a></li>
          <li><a href="clientCerfa">Client Cerfa</a></li>
          <li><a href="produitCerfa">Produit Cerfa</a></li>
          </li>
        </ul>
      </li> -->
    

      <!----------------------------------------------------------- 
    Etudiant Content -->
    <?php elseif ($_SESSION['user']['role'] == 1): ?>
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
      <!-- <li class="icon-container close">
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
      </li> -->
      <li class="icon-container close">
        <a href="#">
          <i class="fa-solid fa-book-open"></i>
          <span class="link_name">Anciens Dossiers</span>
        </a>
       
       <?php
        $anneeActuelle = date('Y');
        $anneeDebut = 2024;

        // Option 1: Une seule liste avec tous les éléments
        echo '<ul class="sub-menu blank">';
        for ($annee = $anneeDebut; $annee <= $anneeActuelle; $annee++) {
            echo '<li><a class="link_name" href="#">Année ' . $annee . '</a></li>';
        }
        echo '</ul>';
        ?>

      </li>
      <li class="icon-container close">
        <!-- <a href="../../view/assistance/assistance.php">
          <i class="fa-solid fa-question"></i>
          <span class="link_name">Demande d'assistance</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="../../view/assistance/assistance.php">Demande d'assistance</a></li>
        </ul>
      </li> -->
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
</div>
