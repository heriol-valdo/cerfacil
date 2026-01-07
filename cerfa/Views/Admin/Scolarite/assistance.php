<?php



use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Model\FileHelper;
use Projet\Model\Session;



$url = substr(explode('?', $_SERVER["REQUEST_URI"])[0], 1);

$paginator = new Paginator($pageCourante, $nbrePages, [],"assistance",$search,'search');
App::setTitle("Demande d'assistance");
App::setNavigation("Demande d'assistance");
App::setBreadcumb("<li class='active'>Demande d'assistance</li>");
App::addScript('assets/js/assistance.js',true);


?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                       Historique des tickets <small>(<?= thousand($nbre); ?>)</small>
                </h5>
                <div class="panel-control">
                    <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Faire une demande d'assistance">
                        <i class="icon-plus text-success fa-2x"></i>
                    </a>
                  

                    <!-- <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">
                        <i class="icon-arrow-down fa-2x"></i>
                    </a> -->
                    <a href="<?= App::url('assistance') ?>" data-toggle="tooltip" class="panel-reload" data-original-title="Rafraichir">
                        <i class="icon-reload fa-2x"></i>
                    </a>
                     </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('assistance') ?>" method="POST" autocomplete="off">
                            <div class="row">
                                <div class="col-md-11" >
                                    <div class="form-group">
                                        <input type="text" name="searchassistance" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  l'objet   " class="form-control btn-rounded" title="Chercher par l'object" >
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
                                <thead class="noBackground">
                                <tr>
                                    <th class="">Statut</th>
                                    <th class="">Date</th>
                                    <th class=""> Object </th> 
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="table-Villes">
                                <?php
                                if (!empty($items) && $items!="La propriété 'data' est manquante ou invalide."){
                                            usort($items, function($a, $b) {
                                            // Gestion des dates vides
                                            if (empty($a->dateCreation)) return 1;
                                            if (empty($b->dateCreation)) return -1;
                                    
                                            return strtotime($b->dateCreation) - strtotime($a->dateCreation);
                                        });
                                    foreach ($items as $item) {
                                        ?>
                                        <tr>
                                           
                                            <td class=""><?= StringHelper::isEmpty(StringHelper::$tabetatticket [$item->etat]); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->dateCreation); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->objet); ?> </td>
                                            <td class="text-center">
                                            
                                            <form action="<?= App::url('assistanceDetails') ?>" method="POST" autocomplete="off">
                                                <input type="hidden" name="id" value="<?= $item->id; ?>">
                                                <button type="submit" class="btn btn-link p-0" style="border: none; background: none;">
                                                    <i class="fa fa-info-circle fa-lg text-primary fa-2x" title="Voir les détails"></i>
                                                </button>
                                            </form>
                                                &nbsp

                                            <!-- <a href="javascript:void(0);" class="edit text-success" title="Modifier"
                                                    data-id="<?= $item->id; ?>"


                                                    data-objet="<?= $item->objet; ?>"
                                                    data-telephone="<?= $item->telephone; ?>"
                                                    data-message="<?= $item->description; ?>"
                                                    >
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a> -->
                                                    &nbsp
                                                <!-- <a href="javascript:void(0);" class="trash text-danger" title="Supprimer"
                                                   data-url="<?= App::url('formations/delete'); ?>"
                                                   data-id="<?= $item->id; ?>"><i class="fa fa-trash fa-2x"></i>
                                                </a> -->
                                            </td>
                                        </tr>
                                    <?php } } else{ ?>
                                    <tr>
                                        <td colspan="9" class="text-danger text-center">Liste des tickets vide</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <?php
                                if(!empty($items)){ ?>
                                    <tfoot>
                                    <tr>
                                        <td colspan="9">
                                         
                                              
                                                    <?php  $paginator->paginateTwo(); ?>
                                              
                                           
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
</div>
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intro">Enregistrer une formation</h2>
            </div>
            <form action="<?= App::url('assistance/save') ?>" id="newFrom" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idElement" name="idElement">
                    <input type="hidden" id="action" name="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        
                    <div class="row">
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"> Objet  <b>*</b></label>
                                <input type="text" id="objet" name="objet" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                       <div class="col-md-6">
                            <div class="form-group">
                                
                                <?php if (isset($user->data->telephone) && !empty($user->data->telephone)): ?>
                                    <!-- Affichage en lecture seule si téléphone existe -->
                                     
                                    <input type="hidden" id="telephone" name="telephone" class="form-control" 
                                        value="<?= htmlspecialchars($user->data->telephone) ?>" 
                                        style="border-radius: 5px;" readonly>
                                <?php else: ?>
                                    <!-- Champ éditable si pas de téléphone -->
                                      <label class="control-label">Telephone <b>*</b></label>
                                    <input type="tel" id="telephone" name="telephone" class="form-control" 
                                        value="" 
                                        placeholder="Ex: 0756867021"
                                        pattern="^0[1-9][0-9]{8}$"
                                        style="border-radius: 5px;">
                                <?php endif; ?>
                            </div>
                        </div>
                       
                        
                    </div>

                    <div class="row">
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Message  <b>*</b></label>
                            <textarea id="message" rows="4" name="message" class="form-control" required style="border-radius: 5px;"></textarea>
                        </div>
                    </div>
                   
                   
                    
                </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirm" class="newBtn btn btn-default" style="border-radius: 5px;">AJOUTER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


