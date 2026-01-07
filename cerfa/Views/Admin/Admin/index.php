<?php


use Projet\Database\Vues;
use Projet\Database\Worker;
use Projet\Model\App;
use Projet\Model\DateParser;
use Projet\Model\FileHelper;
use Projet\Model\Paginator;
use Projet\Model\Privilege;
use Projet\Model\StringHelper;

$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);
$paginator = new Paginator($pageCourante, $nbrePages, [],"admins",$search,'searchadmins');
App::setTitle("Les Administrateurs");
App::setNavigation("Les Administrateurs");
App::setBreadcumb('<li class="active">Administrateurs</li>');
App::addStyle('assets/css/multi-select.css',true);
App::addScript('assets/js/jquery.multi-select.js',true);
App::addScript('assets/js/admin.js',true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                    Administrateurs <small>(<?=thousand($nbre); ?>)</small>
                </h5>
                <div class="panel-control">
                 
                        <a href="javascript:void(0);" data-toggle="tooltip" class="new" data-original-title="Nouvel administrateur">
                            <i class="icon-plus text-success fa-2x"></i>
                        </a>
                 
                    <!-- <a target="_blank" href="<?= App::url('admins/pdf') ?>"
                       data-toggle="tooltip" data-original-title="Generer le fichier PDF des admins" ><i class="fa fa-file-pdf-o fa-2x text-primary"></i></a>
                    <a target="_blank" href="<?= App::url('admins/excell') ?>"
                       data-toggle="tooltip" data-original-title="Generer le fichier Excel des admins" ><i class="fa fa-file-excel-o fa-2x text-info"></i></a>
                    <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">
                        <i class="icon-arrow-down fa-2x"></i>
                    </a>
                    <a href="<?= App::url('admins') ?>" data-toggle="tooltip" class="panel-reload" data-original-title="Rafraichir">
                        <i class="icon-reload fa-2x"></i>
                    </a> -->
                </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('admins') ?>" method="POST" autocomplete="off">
                            <div class="row">
                              
                            </div>
                            <div class="row">
                                <div class="col-md-11 form-group">
                                <input type="text" name="searchadmins" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  nom    " class="form-control btn-rounded" title="Chercher par le nom ">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-block btn-default btn-rounded" style="max-width: 120px;" type="submit">Chercher</button>
                                </div>
                               
                            </div>
                           
                        </form>
                    </div>
                </div>
                <div class="row m-t-sm" style="min-height: 470px;">
                    <div class="col-md-12">
                        <div class="table-responsive project-stats">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                   
                                    <th class="">Noms</th>
                                    <th class="">Email</th>
                                    <th class="">Telephone</th>
                                    <th class="">Ville</th>
                                    <th class="">Postal</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($profils)){
                                    foreach ($profils as $profil) {
                                          
                                       

                                        $stat1 = "";
                                        $stat2 = "";
                                        if(($profil->firstname==0||$profil->firstname==2)){
                                            $stat1 = '<li>
                                                        <a href="javascript:void(0);" data-url="'.App::url('admins/activate').'" 
                                                        class="activate" data-etat="'.$profil->firstname.'" data-id="'.$profil->id.'">Activer l\'administrateur</a>
                                                    </li>';
                                        }else{
                                          
                                                $stat1 = '<li>
                                                    <a href="javascript:void(0);" data-url="'.App::url('admins/activate').'" 
                                                    class="activate" data-etat="'.$profil->firstname.'" data-id="'.$profil->id.'">Désactiver l\'administrateur</a>
                                                </li>';
                                            
                                        }
                                        $stat01 = $stat02 = $stat04 = "";
                                        
                                            $stat01 = '<li>
                                                                <a href="javascript:void(0);" data-id="'.$profil->id_users.'"
                                                                 data-nom="'.$profil->firstname.'"  data-prenom="'.$profil->lastname.'"  data-ville="'.$profil->ville.'"
                                                                 data-telephone="'.$profil->telephone.'" data-adresse="'.$profil->adressePostale.'"  data-postal="'.$profil->codePostal.'"
                                                                 data-email="'.$profil->email.'"
                                                                 
                                                                class="edit">Modifier</a>
                                                            </li>';
                                      
                                       
                                            
                                       
                                      
                                            $stat04 = '<li>
                                                                <a href="javascript:void(0);" data-url="'.App::url('admins/reset').'" 
                                                                class="reset" data-id="'.$profil->id.'">Réinitialiser le mot de passe</a>
                                                            </li>';


                                                            $stat03 = '<li>
                                                                <a href="javascript:void(0);" data-url="'.App::url('admins/delete').'" 
                                                                class="delete" data-id="'.$profil->id_users.'">supprimer </a>
                                                            </li>';
                                       
                                        echo
                                            '
                                            <tr>
                                                
                                                <td class="">'.StringHelper::getShortName($profil->firstname,$profil->lastname).'</td>
                                                
                                                <td class="">'.StringHelper::isEmpty($profil->email).'</td>
                                                
                                                <td class="">'.StringHelper::isEmpty($profil->telephone).'</td>

                                                  <td class="">'.StringHelper::isEmpty($profil->ville).'</td>

                                                    <td class="">'.StringHelper::isEmpty($profil->codePostal).'</td>
                                               
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-rounded" data-toggle="dropdown" aria-expanded="false">
                                                            Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right no-scrollbar" role="menu">
                                                            '.$stat01.$stat02.$stat03.'
                                                        </ul>
                                                    </div>
                                                </td>
                                               
                                            </tr>
                                            ';
                                    }}else{
                                    echo '<tr><td colspan="9" class="text-danger text-center">Liste des administrateurs vide ...</td></tr>';
                                }
                                ?>
                                </tbody>
                                <?php
                                if(!empty($profils)){ ?>
                                    <tfoot>
                                    <tr>
                                        <td colspan="9">
                                            <?php $paginator->paginateTwo(); ?>
                                        </td>
                                    </tr>
                                    </tfoot>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade newModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title titleForm">Titre</h2>
            </div>
            <form action="<?= App::url('admins/save') ?>" id="newForm" method="post">
                <div class="modal-body">
                    <input type="hidden" id="action">
                    <input type="hidden" id="idElement">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nom">Nom <b>*</b></label>
                            <input type="text" class="form-control" id="nom" placeholder="Nom" required style="border-radius: 5px;">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="prenom">Prénom <b>*</b></label>
                            <input type="text" class="form-control" id="prenom" placeholder="Prénom" required style="border-radius: 5px;">
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 form-group">
                            <label for="email">Adresse email<b>*</b></label>
                            <input type="email" class="form-control" id="email" placeholder="Adresse email" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="Adresse postal">Adresse postal<b>*</b></label>
                            <input type="text" class="form-control" id="adresse" placeholder="Adresse postal" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="code postal">code postal<b>*</b></label>
                            <input type="text" class="form-control" id="postal" placeholder="code postal" required style="border-radius: 5px;">
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="Ville">Ville<b>*</b></label>
                            <input type="text" class="form-control" id="ville" placeholder="ville" required style="border-radius: 5px;">
                        </div>
                        
                        <div class="col-md-6 form-group">
                            <label for="telephone">Telephone<b>*</b></label>
                            <input type="text" class="form-control" id="telephone" placeholder="Telephone" required style="border-radius: 5px;">
                        </div>
                       
                    </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="submit" class="newBtn btn btn-default" style="border-radius: 5px;">Ajouter</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade photoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title">METTRE LA PHOTO A JOUR</h2>
            </div>
            <form action="<?= App::url('admins/setPhoto') ?>" id="photoForm" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idPhoto" name="idPhoto">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="photoImage">Photo <b>*</b></label>
                            <input type="file" class="form-control" id="photoImage" accept="image/*" name="image">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="photoBtn btn btn-default">MISE A JOUR</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>