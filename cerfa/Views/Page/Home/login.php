<?php
use Projet\Model\App;
use Projet\Model\FileHelper;

$auth = App::getDBAuth();
App::setTitle("Se connecter à l'administration");
App::addScript("assets/js/login.js", true);

$encodedToken = isset($_GET['token']) ? $_GET['token'] : "";
$encodedUser = isset($_GET['user']) ? $_GET['user'] : "";

if ($encodedToken && $encodedUser) {
    // Décoder les paramètres URL encodés
    $decodedTokenJson = urldecode($encodedToken);
    $decodedUserJson = urldecode($encodedUser);

    // Convertir les chaînes JSON décodées en tableaux PHP
    $decodetoken = json_decode($decodedTokenJson, true);
    $decodeuser = json_decode($decodedUserJson, true);

    $auth->writeLogin($decodeuser, $decodetoken);
}

// Vérifiez si l'utilisateur est connecté
if ($auth->isLogged()) {
    // Redirigez l'utilisateur vers la page d'accueil
    header('Location: ' . App::url("home"));
    exit(); // Assurez-vous de terminer le script après la redirection
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Se connecter à l'administration</title>
    <!-- Inclure jQuery depuis un CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= FileHelper::url('assets/js/login.js') ?>"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-P9BT7RE1SD"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-P9BT7RE1SD');
    </script>
    <style>
        @media screen and (min-width: 715px) {
   /* Vos styles pour les écrans plus larges que l'iPhone XR vont ici */
           .mediastation{
            width: 35%;
           }
        }
    </style>
</head>
<body>
    <div class="page-inner" style="background: transparent !important">
        <div  style="margin: auto; height: 100px"> </div>
        <div id="main-wrapper" style="margin-top: 0">
            <div class="row">
                <div class="col-md-3 center divBox btn-rounded  mediastation"  style="background: #fff;   padding: 30px; margin-top: 50px">
                    <div class="login-box">
                        <a href="<?= App::url('') ?>" class="logo-name text-center">
                            <img src="<?= FileHelper::url('assets/img/lgxlogo.jpg') ?>" style="height: auto; width: 200px;" alt="">
                        </a>
                        <p style="color: #00008B; margin-top: 20px;"><h2 class="text-center no-m">CerFacil</h2></p>
                        <form class="m-t-md text-center" action="<?= App::url('ajax') ?>" id="loginForm">
                            
                            <div class="form-group">
                                <input type="text" class="form-control-login btn-rounded" id="login" placeholder="Email "  style="width: 100%;  border: 1px solid #ccc; height: 34px;   padding: 5px 12px;" required>
                            </div>
                            <div class="form-group" style="position: relative; width: 100%;margin-bottom: 20px">
                                <input type="password" class="form-control-login btn-rounded" id="password"
                                    style="width: 100%; border: 1px solid #ccc; height: 34px; padding: 5px 40px 5px 12px;"
                                    placeholder="Mot de passe" required>
                                <span class="toggle-password" onclick="togglePasswordVisibility()"
                                    style="position: absolute; right: 10px; top: 8px; cursor: pointer;">
                                    <i id="toggle-icon" class="fa fa-eye fa-lg"></i>
                                </span>
                            </div>
                            <a href="<?= App::url('resetPasswordSend') ?>"style=" text-decoration: none;">
                                <p class="text-right text-sm" style="margin-top: 20px; color:#C1C5C9;">Mot de passe oublié ?</p> 
                            </a>
                            

                            <button type="submit" style="background: #153C4A;" class="sendBtn btn btn-success btn-lg btn-rounded" translate="no">Se connecter</button>
                        </form>
                        <p class="text-center text-sm" style="margin-top: 20px">2023 &copy; Développé par CerFacil </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
