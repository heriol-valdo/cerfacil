<?php
use Projet\Model\App;
use Projet\Model\FileHelper;

$auth = App::getDBAuth();
App::setTitle("Récupération du mot de passe");
App::addScript("assets/js/resetPasswordSend.js", true);





?>
<!DOCTYPE html>
<html>
<head>
    <title>Récupération du mot de passe</title>
    <!-- Inclure jQuery depuis un CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= FileHelper::url('assets/js/login.js') ?>"></script>
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
                        <p style="color: #00008B; margin-top: 35px;"><h1 class="text-center no-m">Mot de passe oublié ?</h1></p>

                        <p style="color: #00008B; margin-top: 25px;"><h5 class="text-center no-m">Nous vous enverrons un lien de récupération par mail.
                         Attention, il n'est valable que pendant 15 minutes.</h5></p>

                        <form class="m-t-md text-center" action="<?= App::url('ajaxresetPasswordSend') ?>" id="resetFormSend">
                            
                            <div class="form-group">
                                <input type="text" class="form-control-login btn-rounded text-center" id="email" placeholder="Email "  style="width: 85%;  border: 1px solid #ccc; height: 34px;   padding: 5px 12px;" required>
                            </div>
                             
                            
                            <a href="<?= App::url('') ?>"style=" text-decoration: none;">
                              <button type="button" style="background: #153C4A;margin-right:20px; min-width:100px;" class="sendBtn btn btn-success btn-lg btn-rounded" translate="no">Retour</button>
                            </a>

                            <button type="submit" style="background: #153C4A;min-width:100px;" class="sendBtn btn btn-success btn-lg btn-rounded newBtn" translate="no">Envoyer le lien de récupération</button>
                        </form>
                        <p class="text-center text-sm" style="margin-top: 20px">2023 &copy; Développé par CerFacil </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
