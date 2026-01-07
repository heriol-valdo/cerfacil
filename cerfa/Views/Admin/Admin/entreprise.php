<?php



use Projet\Database\Opco;
use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;


$url = substr(explode('?',$_SERVER["REQUEST_URI"])[0],1);
$paginator = new Paginator($pageCourante, $nbrePages, [],"employeurs",$search,'searchentreprise');
App::setTitle("Les EMPLOYEURS");
App::setNavigation("Les EMPLOYEURS");
App::setBreadcumb('<li class="active">EMPLOYEURS</li>');
App::addScript('assets/js/entreprise.js',true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                 EMPLOYEURS<small>(<?= thousand($nbre); ?>)</small>
                </h5>
                <div class="panel-control">
                    <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Nouveau employeur">
                        <i class="icon-plus text-success fa-2x"></i>
                    </a>
                  

                    <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">
                        <i class="icon-arrow-down fa-2x"></i>
                    </a>
                    <a href="<?= App::url('employeurs') ?>" data-toggle="tooltip" class="panel-reload" data-original-title="Rafraichir">
                        <i class="icon-reload fa-2x"></i>
                    </a>
                     </div>
            </div>
            <div class="panel-body">
                <div class="row m-t-sm">
                    <div class="col-md-12">
                        <form action="<?= App::url('employeurs') ?>" method="POST" autocomplete="off">
                            <div class="row">
                                <div class="col-md-11" >
                                    <div class="form-group">
                                    <input type="text" name="searchentreprise" style="border-radius: 10px;border: 1px solid #ccc;" <?= !empty($search)?'value="'.$search.'"':''; ?> placeholder="Chercher par  nom    " class="form-control btn-rounded" title="Chercher par le nom ">
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
                                    <th class="">Nom </th>
                                    <th class="">Email </th>
                                    <th class="">Numero </th>
                                    <th class=""> Type Employeur</th>
                                    <th class=""> N°SIRET</th>
                                    <th class=""> Opco responsable</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="table-Villes">
                                <?php
                                function opco($id){
                                    $ligneopco = Opco::find($id);
                                    if( $ligneopco['valid']){
                                       return  $nomopco = $ligneopco['data']->nom;
                                    }else{
                                      return   $nomopco = "";
                                    }
                                }
                                if (!empty($items)){
                                    foreach ($items as $item) {
                                       $nomopco = opco($item->idopco);

                                        ?>
                                        <tr>
                                            <td class=""><?= StringHelper::isEmpty($item->nomE); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->emailE); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->numeroE); ?></td>
                                            <td class=""><?=  StringHelper::isEmpty($item->typeE); ?></td>
                                            <td class=""><?= StringHelper::isEmpty($item->siretE);  ?></td>
                                            <td class=""><?= StringHelper::isEmpty($nomopco);  ?></td>
                                            <td class="text-center">
                                            <a href="javascript:void(0);" class="edit text-success"  title="Modifier"
                                                    data-nomE="<?= $item->nomE; ?>"
                                                    data-id="<?= $item->id; ?>"

                                                    data-typeE="<?= $item->typeE; ?>"
                                                    data-specifiqueE="<?= $item->specifiqueE; ?>"
                                                    data-totalE="<?= $item->totalE; ?>"
                                                    data-siretE="<?= $item->siretE; ?>"
                                                    data-codeaE="<?= $item->codeaE; ?>"
                                                    data-codeiE="<?= $item->codeiE; ?>"

                                                    data-rueE="<?= $item->rueE; ?>"
                                                    data-voieE="<?= $item->voieE; ?>"
                                                    data-complementE="<?= $item->complementE; ?>"
                                                    data-postalE="<?= $item->postalE; ?>"
                                                    data-communeE="<?= $item->communeE; ?>"
                                                    data-emailE="<?= $item->emailE; ?>"
                                                    data-numeroE="<?= $item->numeroE; ?>"
                                                    data-idopco="<?= $item->idopco; ?>"
                                                    >
                                                        <i class="fa fa-edit fa-2x"></i>
                                                    </a>
                                                    &nbsp
                                                <a href="javascript:void(0);" class="trash text-danger" title="Supprimer"
                                                   data-url="<?= App::url('employeurs/delete'); ?>"
                                                   data-id="<?= $item->id; ?>"><i class="fa fa-trash fa-2x"></i>
                                                </a> 
                                                &nbsp
                                                <a href="javascript:void(0);" class="send text-info" title="Envoyer"
                                                   data-url="<?= App::url('employeurs/sendEmail'); ?>"
                                                   data-id="<?= $item->id; ?>"><i class="fa fa-envelope fa-2x"></i>
                                                </a>

                                                

                                              

                                              
                                            </td>
                                        </tr>
                                    <?php } } else{ ?>
                                    <tr>
                                        <td colspan="9" class="text-danger text-center">Liste des employeurs  vide</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <?php
                                if(!empty($items)){ ?>
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
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intro">Enregistrer une entreprise</h2>
            </div>
            <form action="<?= App::url('employeurs/save') ?>" id="newFrom" method="post">
                <div class="modal-body">
                    <input type="hidden" id="idElement">
                    <input type="hidden" id="action">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    <div class="row">
                        <p class="mainColor text-left">Employeur</p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nom et prénom ou dénomination :  <b>*</b></label>
                                <input type="text" id="nomE"   name="nomE" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Type d’employeur: </label>
                                <select id="typeE" name="typeE" class="form-control" style="border-radius: 5px;">
                                     <option value="">_________</option>
                                    <optgroup label="Privé">
                                        <option value="11">11 :Entreprise inscrite au répertoire des métiers ou au registre des entreprises pour l’Alsace-Moselle</option>
                                        <option value="12">12 :Entreprise inscrite uniquement au registre du commerce et des sociétés</option>
                                        <option value="13">13 :Entreprises dont les salariés relèvent de la mutualité sociale agricole</option>
                                        <option value="14">14 :Profession libérale</option>
                                        <option value="15">15 :Association</option>
                                        <option value="16">16 :Autre employeur privé</option>
                                    </optgroup>
                                    <optgroup label="Public">
                                        <option value="21">21 :Service de l’Etat (administrations centrales et leurs services déconcentrés de la fonction publique d’Etat)</option>
                                        <option value="22">22 :Commune</option>
                                        <option value="23">23 :Département</option>
                                        <option value="24">24 :Région</option>
                                        <option value="25">25 :Etablissement public hospitalier</option>
                                        <option value="26">26 :Etablissement public local d’enseignement</option>
                                        <option value="27">27 :Etablissement public administratif de l’Etat</option>
                                        <option value="28">28 :Etablissement public administratif local (y compris établissement public de coopération intercommunale EPCI)</option>
                                        <option value="29">29 :Autre employeur public</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Employeur spécifique :   </label>
                                <select id="specifiqueE" name="specifiqueE" class="form-control"  style="border-radius: 5px;">
                                    <option value="">_________</option>
                                    <option value="1">1 :Entreprise de travail temporaire</option>
                                    <option value="2">2 :Groupement d’employeurs</option>
                                    <option value="3">3 :Employeur saisonnier</option>
                                    <option value="4">4 :Apprentissage familial : l’employeur est un ascendant de l’apprenti</option>
                                    <option value="0">0 :Aucun de ces cas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Effectif total salariés :  </label>
                                <input type="number" id="totalE"  name="totalE" class="form-control"  style="border-radius: 5px;">
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="row">
                    <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-size:11px;" class="control-label">N°SIRET de l’établissement d’exécution du contrat : </label>
                                <input type="number" id="siretE"  name="siretE" class="form-control"  style="border-radius: 5px;">
                                <small class="form-text text-muted">Le numéro SIRET doit contenir exactement 14 chiffres</small>
                            </div>
                        </div>
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label style="font-size:11px;"class="control-label">Code activité de l’entreprise (NAF) :  </label>
                                <input type="text" id="codeaE"  name="codeaE" class="form-control"  style="border-radius: 5px;">
                                <small class="form-text text-muted">Le code NAF ne doit pas dépasser 6 caractères</small>
                                
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Code IDCC de la convention collective applicable : </label>
                                <input type="text" id="codeiE" name="codeiE" class="form-control"  style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       
                      
                       
                    </div>
                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left  ">Adresse de l’établissement d’exécution du contrat : <b>*</b></p>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="rueE" name="rueE" class="form-control" placeholder="N° "  style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <input type="text" id="voieE" name="voieE" class="form-control"  placeholder="Voie "   style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                               <input type="text" id="complementE"  name="complementE" class="form-control" placeholder="Complement" style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" id="postalE"  name="postalE" class="form-control" placeholder="Code Postal "  style="border-radius: 5px;">
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      
                       
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="communeE" name="communeE" class="form-control"  placeholder="Commune "    style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                <input type="email" id="emailE" name="emailE" class="form-control" placeholder="Courriel *" required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                <input type="number" id="numeroE"  name="numeroE" class="form-control"  placeholder="Téléphone "  style="border-radius: 5px;">
                                <small class="form-text text-muted">Veuillez entrer exactement 10 chiffres</small>
                            </div>
                        </div>
                       
                    </div>


                    <div class="row">
                        <p class="mainColor text-left">Opco responsable de l'entreprise</p>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nom :  </label>
                                <select name="idopco" id="idopco" class="form-control" style="border-radius: 5px;">
                                <option value="">............</option>
                                <?php
                                foreach ($opcos as $opco){
                                    echo '<option value="'.$opco->id.'">'.$opco->nom.'</option>';
                                }
                                ?>
                            </select>
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


