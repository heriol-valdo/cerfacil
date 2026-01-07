<?php

use Projet\Model\App;
use Projet\Model\FileHelper;


App::setTitle("Modifier son mot de passe");
App::setNavigation("Modifier son mot de passe");
App::setBreadcumb('<li class="active">Modifier son mot de passe</li>');
App::addScript('assets/js/password.js',true);
?>
<div class="row">
<div class="col-md-6 center" style="border-radius: 10px;border: 5px solid #fff;  background-color:white;">
        <div class="center">
            <a href="<?= App::url(''); ?>" class="logo-name text-lg text-center">
                <img src="<?= FileHelper::url('assets/img/user.jpg') ?>" class="img-circle img-md center" alt="" style="height: 140px; width: 140px;">
            </a>
            <p class="text-center m-t-md">Modifier votre mot de passe.</p>
            <form class="m-t-md" action="<?= App::url('password/change') ?>" id="changePasswordForm">
                <div class="row ">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="password" class="form-control text-center" id="oldPassword" placeholder="Mot de passe actuel" required>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        <input type="password" class="form-control text-center" id="newPassword" placeholder="Nouveau mot de passe" required>
                    </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="password" class="form-control text-center" id="confirmPassword" placeholder="Confirmer le mot de passe" required>
                        </div>
                    </div>
                </div>
               
               

                <div class="button-container">
                    <button type="submit"     class="profil-footer sendBtn">Modifier</button>
                    <a href="<?= App::url('profil'); ?>" class="button-link">  
                      <button type="button" class="profil-footer" id="updatePassword">Annuler </button>
                    </a>
                   
                  
                </div>
            </form>
        </div>
    </div>
</div>
