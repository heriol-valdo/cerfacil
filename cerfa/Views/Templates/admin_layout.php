<?php



use Projet\Model\App;
use Projet\Model\FileHelper;
use Projet\Model\Privilege;
use Projet\Model\Session;
use Projet\Model\StringHelper;
use Projet\Database\Abonnement;
use Projet\Database\Produit;

$session = Session::getInstance();
if (!isset($_SESSION['page_active'])) {
    $_SESSION['page_active'] = 'home'; // Définissez la page par défaut
}

// Récupérez la page active depuis la session
$page = $_SESSION['page_active'];


$abonnements = Abonnement::searchType();
$abonnements = $abonnements['data'];


$hasType1Product = false;

if (!empty($abonnements)) {
    foreach ($abonnements as $abonnement) {
        $tableauproduits = Produit::find($abonnement->id_produit);
        $tableauproduit = $tableauproduits['data'];
    

        if ($tableauproduit->type == 1) {
            $dateCourante  = date("Y-m-d");
            $hasType1Product = ($dateCourante > $abonnement->date_fin)?false : true;
            break; 
        }
    }
}



?>
<!doctype html>
<html lang="fr">
<head>
   <script src="https://js.stripe.com/v3/"></script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no"/>
    <link rel="icon" type="image/png" href="<?= FileHelper::url('assets/img/fav.png') ?>" sizes="16x16">
    <link rel="icon" type="image/png" href="<?= FileHelper::url('assets/img/fav.png') ?>" sizes="32x32">
   

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P9BT7RE1SD"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-P9BT7RE1SD');
    </script>
    <title><?= App::getTitle(); ?></title>

    <?php

App::addScript('assets/plugins/flot/jquery.flot.min.js',true);
App::addScript('assets/plugins/flot/jquery.flot.tooltip.min.js',true);
App::addStyle('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',true);
// App::addScript('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',true);
// App::addScript('https://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js',true);
App::addScript('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',true);
//App::addScript("assets/js/modern.min.js",true, true);
App::addScript("assets/js/loader.js",true, true);



    
    App::addStyle("assets/plugins/pace-master/themes/blue/pace-theme-flash.css",true, true);
    App::addStyle("assets/plugins/uniform/css/uniform.default.min.css",true, true);
    App::addStyle("assets/plugins/bootstrap/css/bootstrap.min.css",true, true);
    App::addStyle("assets/plugins/fontawesome/css/font-awesome.css",true, true);
    App::addStyle("assets/plugins/line-icons/simple-line-icons.css",true, true);
    App::addStyle("assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css",true, true);
    App::addStyle("assets/plugins/waves/waves.min.css",true, true);
    App::addStyle("assets/plugins/switchery/switchery.min.css",true, true);
    App::addStyle("assets/plugins/3d-bold-navigation/css/style.css",true, true);
    App::addStyle("assets/plugins/slidepushmenus/css/component.css",true, true);
    App::addStyle("assets/plugins/weather-icons-master/css/weather-icons.min.css",true, true);
    App::addStyle("assets/plugins/toastr/toastr.min.css",true, true);
    App::addStyle('assets/plugins/summernote-master/summernote.css',true);

    App::addStyle("assets/css/waitMe.min.css",true, true);
    App::addStyle("assets/css/modern.min.css",true, true);
    App::addStyle("assets/css/themes/green.css",true, true);
    App::addStyle("assets/css/sweetalert.css",true, true);
    App::addStyle("assets/css/custom.css",true, true);
    App::addStyle("assets/css/loader.css",true, true);
    App::addStyle("assets/plugins/bootstrap-datepicker/css/datepicker3.css",true, true);
  
    if(!empty(App::getStyles()['default'])){
        foreach (App::getStyles()['default'] as $default) {
            echo $default;
        }
    }
    if(!empty(App::getStyles()['source'])){
        foreach (App::getStyles()['source'] as $source) {
            echo $source;
        }
    }
    if(!empty(App::getStyles()['script'])){
        foreach (App::getStyles()['script'] as $style) {
            echo $style;
        }
    }
    ?>

  

<script>
  function toggleChatbox() {
    const chatbox = document.getElementById('chatbox');
    const chatBody = document.getElementById('chatBody');
    if (chatbox.style.display === 'none' || !chatbox.style.display) {
      chatbox.style.display = 'flex';

      // Ajouter un message de bienvenue une seule fois
      if (!chatBody.dataset.welcomeShown) {
        const botMsg = document.createElement('div');
        botMsg.className = 'msg bot';
        botMsg.innerHTML = '<img class="icon" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png"/> Bonjour ! En quoi puis-je vous aider ?';
        chatBody.appendChild(botMsg);
        chatBody.dataset.welcomeShown = 'true';
        scrollToBottom();
      }
    } else {
      chatbox.style.display = 'none';
    }
    scrollToBottom();
  }

  function scrollToBottom() {
    const chatBody = document.getElementById('chatBody');
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  async function fetchOpenAIResponse(message) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: 'assistanceIA', // ← ta route backend
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ message: message }),
      success: function (response) {
        resolve(response.reply); // On attend que la route retourne { reply: "..." }
      },
      error: function (xhr, status, error) {
        console.error("Erreur serveur :", error);
        resolve("Désolé, une erreur est survenue lors de la communication avec le serveur.");
      }
    });
  });
}

 async function sendMessage() {
  const input = document.getElementById('chatInput');
  const sendBtn = document.getElementById('sendBtn');
  const message = input.value.trim();
  if (!message) return;

  const chatBody = document.getElementById('chatBody');

  // Message utilisateur
  const userMsg = document.createElement('div');
  userMsg.className = 'msg user';
  userMsg.textContent = message;
  chatBody.appendChild(userMsg);
  scrollToBottom();

  input.value = "";
  sendBtn.disabled = true;
  input.disabled = true;

  // Animation de typing
  const typingIndicator = document.createElement('div');
  typingIndicator.className = 'msg bot';
  typingIndicator.innerHTML = `
    <img class="icon" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png"/>
    <span class="typing-animation">
      <span></span><span></span><span></span>
    </span>`;
  chatBody.appendChild(typingIndicator);
  scrollToBottom();

  // -------- OPTION A: RÉPONSE SIMULÉE APRÈS 3 SEC --------
//   setTimeout(() => {
//     typingIndicator.remove();

//     const botMsg = document.createElement('div');
//     botMsg.className = 'msg bot';
//     botMsg.innerHTML = '<img class="icon" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png"/> Voici ma réponse simulée.';

//     chatBody.appendChild(botMsg);
//     scrollToBottom();
//     input.disabled = false;
//     sendBtn.disabled = false;
//     input.focus();
//   }, 3000);

  // -------- OPTION B: VRAI APPEL À L’API D’OPENAI --------
  
  const reply = await fetchOpenAIResponse(message);
  typingIndicator.remove();

  const botMsg = document.createElement('div');
  botMsg.className = 'msg bot';
  botMsg.innerHTML = '<img class="icon" src="https://cdn-icons-png.flaticon.com/512/4712/4712100.png"/> ' + reply;
  chatBody.appendChild(botMsg);
  scrollToBottom();

  input.disabled = false;
  sendBtn.disabled = false;
  input.focus();
  
}

</script>


</head>
<body class="page-header-fixed" >
    <div class="overlay"></div>
    <div class="menu-wrap">
        <button class="close-button" id="close-button">Close Menu</button>
    </div>
    <form class="search-form" action="#" method="post">
        <div class="input-group">
            <input type="text" name="search" class="form-control search-input" placeholder="Chercher...">
            <span class="input-group-btn">
                <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button"><i class="fa fa-times"></i></button>
            </span>
        </div>
    </form>
    <main class="page-content content-wrap" >
        <div class="navbar">
            <div class="navbar-inner">
                <div class="sidebar-pusher">
                    <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <div class="logo-box">
                    <a href="<?= App::url('home') ?>" class="logo-text" style="color: #FFF;font-size: 20px">
                        <b>CerFacil </b>
                    </a>
                </div>
                <!-- <div class="search-button">
                    <a href="javascript:void(0);" class="waves-effect waves-button waves-classic show-search"><i class="fa fa-search"></i></a>
                </div> -->
                <div class="topmenu-outer"  >
                    <div class="top-menu">
                        <ul class="nav navbar-nav navbar-left">
                            <li>
                                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                            </li>
                            <li>
                                <a href="#cd-nav" class="waves-effect waves-button waves-classic cd-nav-trigger"><i class="fa fa-diamond"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect waves-button waves-classic toggle-fullscreen"><i class="fa fa-expand"></i></a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                                <?php $userPhoto = $user == "" ? "" : FileHelper::url('assets/img/user.jpg');?>
                                    <img class="img-circle avatar" src="<?= $userPhoto ?>" width="40" height="40" alt="">

                                    <span class="user-name"><?= $user->data->firstname." ".$user->data->lastname; ?><i class="fa fa-angle-down"></i></span>
                                </a>
                                <ul class="dropdown-menu dropdown-list" role="menu">
                                    <li role="presentation"><a href="<?= App::url('profil') ?>"><i class="fa fa-lock"></i>Profil</a></li>
                                    <li role="presentation" class="divider"></li>
                                    <li role="presentation"><a href="<?= App::url('logout') ?>"><i class="fa fa-sign-out m-r-xs"></i>Déconnexion</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-sidebar sidebar" >
            <div class="page-sidebar-inner slimscroll">
                <div class="sidebar-header">
                    <div class="sidebar-profile">
                        <a href="javascript:void(0);" id="profile-menu-link">
                            <div class="sidebar-profile-image" >
                              <img src="<?= FileHelper::url('assets/img/lgxlogo.jpg') ?>" class=""  style="height: auto; width: 100px;" alt="">
                            </div>

                        </a>
                    </div>
                </div>
                <ul class="menu accordion-menu fixe">
                  
                        <li<?= $page=='home'?' class="active"':''; ?>>
                            <a href="<?= App::url('home'); ?>" class="waves-effect waves-button"  data-page="home" >
                                <span class="menu-icon glyphicon glyphicon-home"></span>
                                <p>Tableau de bord</p>
                            </a>
                        </li>
                    

                   
                        <li data-toggle="tooltip"  data-original-title="<?= !$hasType1Product ? 'Veuillez recharger votre Abonnement' : ''; ?>" 
                         class="droplink<?= $page=='alternants' || $page=='cerfas'  || $page=='formations' ||  $page=='opco' || $page=='entreprises'?' active open':''; ?>">
       
                            <a href="javascript:void(0);" class="waves-effect waves-button">
                                <span class="menu-icon icon-bag"></span>
                                <p>Dossier Apprentissage</p>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu  <?= !$hasType1Product ? ' disabled' : ''; ?>"  >
                               
                                    <li<?= $page=='cerfas'?' class="active"':'';  ?>>
                                        <a href="<?= App::url('cerfas') ?>" class="waves-effect waves-button"   data-page="cerfas" >
                                           Dossiers
                                        </a>
                                    </li>

                                   
                               
                               <!-- <li<?= $page=='admins'?' class="active"':''; ?>>
                                   <a href="<?= App::url('admins') ?>" class="waves-effect waves-button" data-page="admins" onclick="ok(this)">
                                       Administrateurs
                                   </a>
                               </li> -->
                          

                         
                               <li<?= $page=='formations'?' class="active"':''; ?>>
                                   <a href="<?= App::url('formations') ?>" class="waves-effect waves-button" data-page="formations" >
                                      Formations
                                   </a>
                               </li>
                          

                                                             
                                    <!-- <li<?= $page=='alternants'?' class="active"':''; ?>>
                                        <a href="<?= App::url('alternants') ?>" class="waves-effect waves-button" data-page="alternants" onclick="ok(this)">
                                           Alternants
                                        </a>
                                    </li> -->
                         
                               <li<?= $page=='entreprises'?' class="active"':''; ?>>
                                   <a href="<?= App::url('employeurs') ?>" class="waves-effect waves-button" data-page="entreprises" >
                                      Employeurs
                                   </a>
                               </li>
                           

                        
                               <li<?= $page=='opco'?' class="active"':''; ?>>
                                   <a href="<?= App::url('opco') ?>" class="waves-effect waves-button" data-page="opco" >
                                      Opco
                                   </a>
                               </li>
                          
                            </ul>
                        </li>
                   


                    
                      
                  
                       <?php if($user->data->role == 'Gestionnaire de centre'){ ?>
                       
                       
                        <li class="droplink<?= $page=='admins' || $page=='produits'?' active open':''; ?>">
                            <a href="javascript:void(0);" class="waves-effect waves-button">
                                <span class="menu-icon fa fa-user-secret"></span>
                                <p>Market</p>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                               
                                    <li<?= $page=='produits'?' class="active"':''; ?>>
                                        <a href="<?= App::url('produits') ?>" class="waves-effect waves-button" data-page="produits">
                                            Produits
                                        </a>
                                    </li>
                                  
                                
                            </ul>
                        </li>
                       
                       
                      <?php } ?>

                      <?php if($user->data->role == 'ClientCERFA'){ if($user->data->roleCreation ==1){?>
                       
                       
                       <li class="droplink<?= $page=='admins' || $page=='produits'?' active open':''; ?>">
                           <a href="javascript:void(0);" class="waves-effect waves-button">
                               <span class="menu-icon fa fa-user-secret"></span>
                               <p>Market</p>
                               <span class="arrow"></span>
                           </a>
                           <ul class="sub-menu">
                              
                                   <li<?= $page=='produits'?' class="active"':''; ?>>
                                       <a href="<?= App::url('produits') ?>" class="waves-effect waves-button" data-page="produits">
                                           Produits
                                       </a>
                                   </li>

                                  
                                   <li<?=  $page=='admins'?' class="active"':''; ?>>
                                       <a href="<?= App::url('admins') ?>" class="waves-effect waves-button" data-page="admins" >
                                           Administrateurs
                                       </a>
                                   </li>
                              
                                 
                               
                           </ul>
                       </li>
                      
                      
                       <?php }} ?>

                      

                        <li class="droplink<?= $page=='assistance' || $page=='simulateur' ?' active open':''; ?>">
                            <a href="javascript:void(0);" class="waves-effect waves-button">
                               <span class="menu-icon fa fa-cogs"></span>  
                                <p>Assistance</p>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                               
                                <li<?= $page=='assistance'?' class="active"':''; ?>>
                                    <a href="<?= App::url('assistance'); ?>" class="waves-effect waves-button"  data-page="assistance" >
                                        <p>Demande d'assistance</p>
                                    </a>
                                </li>

                                  <li<?= $page=='simulateur'?' class="active"':''; ?>>
                                    <a href="<?= App::url('simulateur'); ?>" class="waves-effect waves-button"  data-page="simulateur" >
                                        <p>Simulateur</p>
                                    </a>
                                </li>
                                  
                                
                            </ul>
                        </li>
                  
                </ul>
            </div>
        </div>
       
        <div class="page-inner">
            <div class="page-title"  style="   background:  #E9E9E9;">
                <h3 style="   color:  red;"><?= App::getNavigation(); ?></h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                    <?= App::getBreadcumb();?>
                    </ol>
                </div>
            </div>
            <div id="main-wrapper" >
                <?php
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span class="alertJss">' . $session->read('success') . '</span></div>';
                    $session->delete('success');
                }
                if (isset($_SESSION['danger'])) {
                    echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span class="alertJs">' . $session->read('danger') . '</span></div>';
                    $session->delete('danger');
                }
                echo '<div class="alerter alert alert-success alert-dismissible text-center hide" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><span class="alertJsText">' . $session->read('success') . '</span></div>';
                echo '<div>' .$content. '</div>';
                
                ?>
            </div>

            <!-- Bouton flottant pour afficher le chat -->
            <button id="chatToggleBtn" onclick="toggleChatbox()" class="chat-toggle" title="Assistant IA">💬</button>

            <!-- Boîte de dialogue -->
            <div id="chatbox">
            <div class="chat-header">CerFacil Assistant</div>
            <div class="chat-body" id="chatBody">
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Tapez votre message..." />
                <button id="sendBtn" onclick="sendMessage()">➤</button>
            </div>
            </div>



            <div class="page-footer">
                <p class="no-s">2023 &copy; Développé par CerFacil</p>
            </div>
        </div>
    </main>
    <div class="cd-overlay"></div>

<?php
App::addScript("assets/plugins/jquery/jquery-2.1.4.min.js",true, true);
App::addScript("assets/plugins/jquery-ui/jquery-ui.min.js",true, true);
App::addScript("assets/plugins/pace-master/pace.min.js",true, true);
App::addScript("assets/plugins/jquery-blockui/jquery.blockui.js",true, true);
App::addScript("assets/plugins/bootstrap/js/bootstrap.min.js",true, true);
App::addScript("assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js",true, true);
App::addScript("assets/plugins/switchery/switchery.min.js",true, true);
App::addScript("assets/plugins/uniform/jquery.uniform.min.js",true, true);
App::addScript("assets/plugins/offcanvasmenueffects/js/classie.js",true, true);
App::addScript("assets/plugins/offcanvasmenueffects/js/main.js",true, true);
App::addScript("assets/plugins/waves/waves.min.js",true, true);
App::addScript("assets/plugins/3d-bold-navigation/js/main.js",true, true);
App::addScript("assets/plugins/waypoints/jquery.waypoints.min.js",true, true);
App::addScript("assets/plugins/jquery-counterup/jquery.counterup.min.js",true, true);
App::addScript("assets/js/sweetalert.min.js",true, true);
App::addScript("assets/js/waitMe.min.js",true, true);
App::addScript("assets/js/modern.min.js",true, false);
App::addScript("assets/plugins/toastr/toastr.min.js",true, true);
App::addScript("assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js",true, true);
App::addScript('assets/plugins/summernote-master/summernote.min.js',true);

App::addScript("assets/js/inits.js",true, true);
if(!empty(App::getScripts()['default'])){
    foreach (App::getScripts()['default'] as $default) {
        echo $default.PHP_EOL;
    }
}
if(!empty(App::getScripts()['source'])){
    foreach (App::getScripts()['source'] as $source) {
        echo $source.PHP_EOL;
    }
}
if(!empty(App::getScripts()['script'])){
    foreach (App::getScripts()['script'] as $script) {
        echo $script.PHP_EOL;
    }
}
?>
</body>
</html>


