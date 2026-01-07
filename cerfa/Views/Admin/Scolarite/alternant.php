<?php


use Projet\Database\Test;
use Projet\Database\Vues;
use Projet\Database\Worker;
use Projet\Model\App;
use Projet\Model\DateParser;
use Projet\Model\FileHelper;
use Projet\Model\Paginator;
use Projet\Model\Privilege;
use Projet\Model\StringHelper;

$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);
$laPage = isset($_GET['page'])?$_GET['page']:1;
$paginator = new Paginator($url,$laPage,$nbrePages,$_GET,$_GET);
App::setTitle("Les Alternants");
App::setNavigation("Les Alternants");
App::setBreadcumb('<li class="active">Alternants</li>');
App::addStyle('assets/css/multi-select.css',true);
App::addScript('assets/js/jquery.multi-select.js',true);
App::addScript('assets/js/alternant.js',true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                   Alternants <small>(<?= thousand($nbre->Total); ?>)</small>
                </h5>
                <div class="panel-control">
                
                        <a href="javascript:void(0);" data-toggle="tooltip" class="new" data-original-title="Nouveau Alternant">
                            <i class="icon-plus text-success fa-2x"></i>
                        </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('alternants') ?>" method="get">
                           
                            <div class="row">
                                <div class="col-md-11" >
                                    <div class="form-group">
                                        <input type="text" name="search" style="border-radius: 10px;border: 1px solid #ccc;" <?= (isset($_GET['search'])&&!empty($_GET['search']))?'value="'.$_GET['search'].'"':''; ?> placeholder="Chercher par  nom" class="form-control btn-rounded" title="Chercher par le nom ">
                                    </div>     
                                </div>
                                <div class="form-group">
                                    <div class="col-md-1">
                                      <button class="btn btn-block btn-default btn-rounded" style="max-width: 120px;" type="submit">Chercher</button>
                                    </div>
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
                                    <th class="">Numéro</th>
                                    <th class="">Email</th>
                                    <th class="">Sexe</th>
                                   
                                    <th class="">Etat</th>
                                   
                                    <th class="text-center">#</th>
                                   
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($alternants)){
                                    foreach ($alternants as $alternant) {
                                      
                                         
                                        $stat1 = "";
                                        
                                       
                                        $stat01 = $stat02 = $stat04 = "";
                                        
                                            $stat01 = '<li>
                                                                <a href="javascript:void(0);" data-id="'.$alternant->id.'"
                                                                 data-nom="'.$alternant->nom.'"  data-prenom="'.$alternant->prenom.'"
                                                                 data-sexe="'.$alternant->sexe.'" data-email="'.$alternant->email.'"
                                                                 data-numero="'.$alternant->numero.'" 
                                                                class="edit">Modifier</a>
                                                            </li>';
                                        
                                        
                                    
                                           

                                              $stat04 = '<li>
                                                           
                                                            <a href="javascript:void(0);" data-url="'.App::url('alternants/delete').'" 
                                                            class="trash" data-id="'.$alternant->id.'">Supprimer alternant</a>
                                                        </li>';
                                        
                                        echo
                                            '
                                            <tr>
                                               
                                                <td class="">'.StringHelper::getShortName($alternant->nom,$alternant->prenom).'</td>
                                                <td class="">'.$alternant->numero.'</td>
                                                <td class="">'.StringHelper::isEmpty($alternant->email,1).'</td>
                                                <td class="">'.$alternant->sexe.'</td>
                                              
                                                <td class="">'.StringHelper::$tabState[$alternant->etat].'</td>
                                               

                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-rounded" data-toggle="dropdown" aria-expanded="false">
                                                            Actions <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right no-scrollbar" role="menu">
                                                            '.$stat04.$stat01.$stat1.'
                                                        </ul>
                                                    </div>
                                                </td>
                                              
                                            </tr>
                                            ';
                                    }}else{
                                    echo '<tr><td colspan="9" class="text-danger text-center">Liste des Alternants  vide ...</td></tr>';
                                }
                                ?>
                                </tbody>
                                <?php
                                if(!empty($alternants)){ ?>
                                    <tfoot >
                                    <tr>
                                        <td colspan="9" >
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
<div class="modal fade newModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="border-radius: 10px;border: 5px solid #fff;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title titleForm">Titre</h2>
            </div>
            <form action="<?= App::url('alternants/save') ?>" id="newForm" method="post">
                <div class="modal-body">
                    <input type="hidden" id="action">
                    <input type="hidden" id="idElement">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="nom">Nom <b>*</b></label>
                            <input type="text" class="form-control" id="nom" placeholder="Nom">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="prenom">Prénom <b>*</b></label>
                            <input type="text" class="form-control" id="prenom" placeholder="Prénom">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="sexe">Sexe <b>*</b></label>
                            <select name="sexe" id="sexe" class="form-control">
                                <option value="">............</option>
                                <option value="Masculin">Masculin</option>
                                <option value="Feminin">Feminin</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="numero">Numéro de téléphone <b>*</b></label>
                            <input type="tel" class="form-control" id="numero" placeholder="Numéro de téléphone">
                        </div>
                        
                    </div>
                    <div class="row">
                        
                        <div class="col-md-6 form-group">
                            <label for="email">Adresse email</label>
                            <input type="email" class="form-control" id="email" placeholder="Adresse email" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="newBtn btn btn-default btn-rounded">Ajouter</button>
                    <button type="button" class="btn btn-warning btn-rounded" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


