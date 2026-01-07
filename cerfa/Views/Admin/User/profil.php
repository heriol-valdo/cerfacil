<?php

use Projet\Model\App;
use Projet\Model\FileHelper;


App::setTitle("Profil");
App::setNavigation("Profil");
App::setBreadcumb('<li class="active">Profil</li>');
App::addScript('assets/js/profil.js',true);
//var_die($user);
?>
<div class="row" >
    <div class="col-md-6 center" style="border-radius: 10px;border: 5px solid #fff;  background-color:white;">
        <div class="center">
            <a href="<?= App::url(''); ?>" class="logo-name text-lg text-center">
                <img src="<?= FileHelper::url('assets/img/user.jpg')?>" class="img-circle img-md  center" alt="" style="height: 140px; width: 140px;">
            </a>
            <p class="text-center m-t-md ">Profil Utilisateur</p>
            <form action="<?= App::url('admins/save') ?>" id="newFrom" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElement" value="<?=$user->data->id_users?>">
                    <input type="hidden" id="action" value="edit">
                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label text-center"   style="margin-left:48%;">Role </label>
                                <input value="<?php 

                                     if($user->data->role == "Gestionnaire de centre"){
                                       echo   "Gestionnaire de centre";
                                     }else{
                                        echo ($user->data->roleCreation == 1)? "Administrateur" : "Sous-Administrateur";
                                     } 
                                     ?>" 
                                
                                class="form-control text-center"  disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nom  </label>
                                <input value="<?= $user->data->firstname?>" class="form-control" id="nom" type="text" disabled  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Prenom </label>
                                <input value="<?= $user->data->lastname?>" class="form-control" type="text" id="prenom" disabled required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input value="<?= $user->data->email?>" class="form-control" type="email" id="email" disabled required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telephone</label>
                                <input value="<?= $user->data->telephone?>" class="form-control" type="number" id="telephone" disabled required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Adresse Postal </label>
                                <input value="<?php 
                                   if($user->data->role == "Gestionnaire de centre"){
                                    echo   $user->data->adresseCentre;
                                  }else{
                                     echo  $user->data->adressePostale;
                                  } 
                               
                                ?>" class="form-control" type="text" id="adresse" disabled required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Ville </label>
                                <input value="<?php 
                                  if($user->data->role == "Gestionnaire de centre"){
                                    echo   $user->data->villeCentre;
                                  }else{
                                     echo    $user->data->ville;
                                  } 
                              
                                ?>" class="form-control" type="text" id="ville" disabled required>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Code Postal</label>
                                <input value="<?php 
                                 if($user->data->role == "Gestionnaire de centre"){
                                    echo   $user->data->codePostalCentre;
                                  }else{
                                     echo    $user->data->codePostal;
                                  }  
                               
                                ?>" class="form-control" type="number" id="postal" disabled required>
                            </div>
                        </div>
                       
                      
                    </div>

                    
                    


                </div>
                <div class="button-container">
                               <?=
                                   ($user->data->role == "Gestionnaire de centre")?"<div style='margin-left:15%;padding-top:1%;'></div>" :
                                     "<button type='button'  id='typebutton'   class='profil-footer typebutton'>Modifier mon compte</button>"
                                    ;
                                  
                               
                                ?>
                   
                    <button   id="annullerSubmit" class="profil-footer text-center" style="display: none;padding-left:10%;padding-top:1%;">Annuler</button>
                    <a href="<?= App::url('password'); ?>" class="button-link">  
                      <button type="button" class="profil-footer" id="updatePassword">Modifier mon mot de passe  </button>
                    </a>
                   
                  
                </div>
            </form>
        </div>
    </div>
</div>


 

